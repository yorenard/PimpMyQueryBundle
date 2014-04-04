<?php

namespace YoRenard\PimpMyQueryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue;
use YoRenard\PimpMyQueryBundle\Form\QueryType;
use YoRenard\BiBundle\Form\FilterType;
use YoRenard\BiBundle\Entity\Filter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * PimpMyQueryController
 */
class PimpMyQueryController extends Controller
{
    protected $pimpMyQueryBusiness;


    public function initController()
    {
        $this->pimpMyQueryBusiness = $this->get('la_fourchette_pimp_my_query.business.pimp_my_query_business');
        $this->pimpMyQueryBusiness->setConnectionName('stat');
    }

    public function listQueryAction($mode=PMQQuery::USER_MODE_USER, $page=1)
    {
        if ($mode==PMQQuery::USER_MODE_ADMIN && !$this->get('security.context')->isGranted('ROLE_STATISTIQUE_EDIT')) {
            throw new AccessDeniedException();
        }

        $filter = new Filter();
        $form = $this->get('form.factory')->create(new FilterType(), $filter);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $queries = $this->pimpMyQueryBusiness->getQueryList($mode, $user, $filter->getName(), $orderBy=PMQQuery::ORDER_BY_FAVORITE, $direction=null, $page);

        $queryList      = $queries['results'];
        $last_page      = ceil($queries['count'] / PMQQuery::NB_QUERY_ON_PAGE);
        $previous_page  = $page>1? $page-1:1;
        $next_page      = $page<$last_page? $page+1:$last_page;

        return $this->render('YoRenardBiBundle:CustomQuery:list.html.twig', array(
            'queryList' => $queryList,
            'form' => $form->createView(),
            'mode' => $mode,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page
        ));
    }

    public function updateFavoriteAction($idQuery)
    {
        $this->initController();

        $this->pimpMyQueryBusiness->updateFavorite($idQuery, $this->get('security.context')->getToken()->getUser());

        return $this->redirect($this->generateUrl('YoRenardBiBundle_CustomQuery_list_show', array('mode' => PMQQuery::USER_MODE_USER)));
    }

    public function editQueryToExecuteAction($idQuery)
    {
        $this->initController();

        if ($this->container->get('request')->isXmlHttpRequest()) {
            $template = 'YoRenardBiBundle:CustomQuery:_form_edit_to_execute.html.twig';
        } else {
            $template = 'YoRenardBiBundle:CustomQuery:edit_to_execute.html.twig';
        }

        return $this->render($template, array(
            'form' => $this->pimpMyQueryBusiness->generateParamForm($idQuery)->createView(),
            'query' => $this->pimpMyQueryBusiness->getQuery($idQuery),
        ));
    }

    public function executeQueryAction($idQuery, $page=1)
    {
        $this->initController();

        $query = $this->pimpMyQueryBusiness->getQuery($idQuery);
        if(!$query) {
            throw new \RuntimeException("This query does'\t exists!!");
        }

        $lfUser = $this->get('security.context')->getToken()->getUser();

        // Test rights
        if(!$this->get('security.context')->isGranted('ROLE_STATISTIQUE_EDIT')) {
            $isValid = false;
            foreach ($query->getRights() as $right) {
                if ($right->getService() == NULL || $right->getService()->getIdService() == $lfUser->getIdService()) {
                    $isValid = true;
                    break;
                }
            }
            if ($isValid===false) {
                throw new AccessDeniedException();
            }
        }

        $request = $this->getRequest();
        $paramForm = $this->get('la_fourchette_pimp_my_query.extractor.param_form')->extract($request);

        // Valid form
        if($this->pimpMyQueryBusiness->validateForm($paramForm)) {
            if($paramForm['mode'] == PMQQuery::EXECUTE_MODE_PLAN) {
                $this->pimpMyQueryBusiness->addRunQueue($query, $paramForm, $lfUser);

                $request->getSession()->setFlash('info', $this->get('translator')->trans('plan.custom_query.alert', array()));

                return $this->redirect($this->generateUrl('YoRenardBiBundle_CustomQuery_list_show', array('mode' => PMQQuery::USER_MODE_USER)));
            }
            else {
                if($paramForm['mode'] == PMQQuery::EXECUTE_MODE_EXPORT) {
                    $runQueue = $this->pimpMyQueryBusiness->addRunQueue($query, $paramForm, $lfUser, array($lfUser->getEmail()));

                    $appPath = $this->container->getParameter('kernel.root_dir');
                    // todo use gearman
                    exec($appPath.'/console lf:pmq:result:export --id_run_queue='.$runQueue->getIdRunQueue().' --delete_run_queue=1 --env='.$this->container->getParameter('kernel.environment').' > '.$appPath.'/logs/pmq.log &');

                    $request->getSession()->setFlash('info', $this->get('translator')->trans('download.custom_query.alert', array()));

                    return $this->redirect($this->generateUrl('YoRenardBiBundle_CustomQuery_list_show', array('mode' => PMQQuery::USER_MODE_USER)));
                }
                else {
                    $startTime = microtime(true);

                    $params = $this->pimpMyQueryBusiness->getParamGenerated($paramForm);
                    $results = $this->pimpMyQueryBusiness->executeQuery($query, $params, $page);

                    $this->pimpMyQueryBusiness->updateSetQuery($query, microtime(true) - $startTime);

                    $pimpMyQueryBusiness = get_class($this->pimpMyQueryBusiness);
                    $last_page      = ceil($results['count'] / $pimpMyQueryBusiness::LIMIT_NB_RESULT);
                    $previous_page  = $page>1? $page-1:1;
                    $next_page      = $page<$last_page? $page+1:$last_page;

                    if(!empty($results['error'])) {
                        $request->getSession()->setFlash('error', $results['error']);
                    }

                    return $this->render('YoRenardBiBundle:CustomQuery:result_query.html.twig', array(
                        'fieldList' => $results['fieldList'],
                        'resultList' => $results['resultList'],
                        'query' => $query,
                        'last_page' => $last_page,
                        'previous_page' => $previous_page,
                        'current_page' => $page,
                        'next_page' => $next_page
                    ));
                }
            }
        } else {
            $request->getSession()->setFlash('error', $this->get('translator')->trans('execute_form.custom_query.error', array()));

            return $this->redirect($this->generateUrl('YoRenardBiBundle_CustomQuery_list_show', array('mode' => PMQQuery::USER_MODE_USER)));
        }
    }
}