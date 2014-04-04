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
 * CRUBController
 */
class CRUBController extends Controller
{
    protected $pimpMyQueryBusiness;

    public function initController()
    {
        $this->pimpMyQueryBusiness = $this->get('la_fourchette_pimp_my_query.business.pimp_my_query_business');
        $this->pimpMyQueryBusiness->setConnectionName('stat');
    }

    public function deleteQueryAction($idQuery)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STATISTIQUE_EDIT')) {
            throw new AccessDeniedException();
        }

        $this->initController();

        $this->pimpMyQueryBusiness->deleteQuery($idQuery);

        $this->getRequest()->getSession()->setFlash('success', $this->get('translator')->trans('query.delete.success', array(), 'validators'));

        return $this->redirect($this->generateUrl('YoRenardBiBundle_CustomQuery_list_show', array('mode' => PMQQuery::USER_MODE_ADMIN)));
    }

    public function editQueryAction($idQuery=null)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STATISTIQUE_EDIT')) {
            throw new AccessDeniedException();
        }

        $this->initController();

        $query = $this->pimpMyQueryBusiness->getQuery($idQuery);
        if(!$query->getRights() || count($query->getRights())==0) {
            $query->addRight(new \YoRenard\PimpMyQueryBundle\Entity\PMQRight());
        }

        if($query->getPublic()) {
            $query->setPublic(false);
        } else {
            $query->setPublic(true);
        }

        $form = $this->createForm(new QueryType(), $query);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            // set originals params
            $originalParams = array();
            foreach ($query->getParams() as $param) {
                $originalParams[] = $param;
            }
            // set originals rigths
            $originalRights = array();
            foreach ($query->getRights() as $rights) {
                $originalRights[] = $rights;
            }

            $form->bind($request);

            // Delete Params not present in form
            foreach($originalParams as $originalParam) {
                $isOriginal = 0;
                foreach($query->getParams() as $formParam) {
                    if($originalParam->getIdParam() == $formParam->getIdParam()) {
                        $isOriginal = 1;
                        break;
                    }
                }

                if(!$isOriginal) {
                    $this->pimpMyQueryBusiness->deleteParam($originalParam);
                }
            }

            // Delete Rights not present in form
            foreach($originalRights as $originalRight) {
                $isOriginal = 0;
                foreach($query->getRights() as $formRight) {
                    if($originalRight->getIdRight() == $formRight->getIdRight()) {
                        $isOriginal = 1;
                        break;
                    }
                }

                if(!$isOriginal) {
                    $this->pimpMyQueryBusiness->deleteRight($originalRight);
                }
            }


            if ($form->isValid()) {
                $this->pimpMyQueryBusiness->saveQuery($query);

                if($query->getIdQuery()) {
                    $request->getSession()->setFlash('success', $this->get('translator')->trans('query.save.success', array(), 'validators'));
                }
                else {
                    $request->getSession()->setFlash('success', $this->get('translator')->trans('query.update.success', array(), 'validators'));
                }
            }
        }

        return $this->render('YoRenardBiBundle:CustomQuery:edit.html.twig', array(
            'form' => $form->createView(),
            'query' => $query
        ));
    }
}