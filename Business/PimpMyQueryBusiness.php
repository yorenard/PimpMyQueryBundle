<?php

namespace LaFourchette\PimpMyQueryBundle\Business;

use Symfony\Component\DependencyInjection\ContainerInterface;
use LaFourchette\PimpMyQueryBundle\Entity\PMQQuery;
use LaFourchette\PimpMyQueryBundle\Entity\PMQParam;
use LaFourchette\PimpMyQueryBundle\Entity\PMQFavoriteQuery;
use LaFourchette\PimpMyQueryBundle\Entity\PMQRunQueue;
use LaFourchette\PimpMyQueryBundle\Entity\PMQRunQueueParam;
use LaFourchette\PimpMyQueryBundle\Manager\PMQQueryManager;
use LaFourchette\PimpMyQueryBundle\Manager\PMQParamManager;
use LaFourchette\PimpMyQueryBundle\Manager\PMQRightManager;
use LaFourchette\PimpMyQueryBundle\Manager\PMQRunQueueManager;
use LaFourchette\PimpMyQueryBundle\Manager\PMQFavoriteQueryManager;
use LaFourchette\PimpMyQueryBundle\Manager\PMQRunQueueParamManager;
use LaFourchette\PimpMyQueryBundle\Form\CustomParamType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LaFourchette\PimpMyQueryBundle\Factory\GeneratedFormFactory;

class PimpMyQueryBusiness
{
    const POST_PARAMETERS_FILE = 'FILE';

    public static $postParameters = array(
        self::POST_PARAMETERS_FILE
    );

    const LIMIT_NB_RESULT = 50;

    protected $connectionName;
    protected $container;
    protected $pmqQueryManager;
    protected $pmqParamManager;
    protected $pmqRightManager;
    protected $pmqRunQueueManager;
    protected $pmqRunQueueParamManager;
    protected $pmqFavoriteQueryManager;


    /**
     * Constructor
     *
     */
    public function __construct(ContainerInterface $container, PMQQueryManager $pmqQueryManager, PMQParamManager $pmqParamManager, PMQRightManager $pmqRightManager, PMQRunQueueManager $pmqRunQueueManager, PMQRunQueueParamManager $pmqRunQueueParamManager, PMQFavoriteQueryManager $pmqFavoriteQueryManager)
    {
        $this->container = $container;
        $this->pmqQueryManager = $pmqQueryManager;
        $this->pmqParamManager = $pmqParamManager;
        $this->pmqRightManager = $pmqRightManager;
        $this->pmqRunQueueManager = $pmqRunQueueManager;
        $this->pmqRunQueueParamManager = $pmqRunQueueParamManager;
        $this->pmqFavoriteQueryManager = $pmqFavoriteQueryManager;
    }

    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
    }

    public function getConnectionName()
    {
        return $this->connectionName;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function deleteQuery($idQuery)
    {
        $query = $this->pmqQueryManager->load($idQuery);

        $this->pmqQueryManager->delete($query);
    }

    public function getParamGenerated(array $param=array())
    {
        $paramGenerated = array();
        foreach($param['generatedParam'] as $key => $generatedParam) {
            $paramGenerated[PMQParam::FIRST_MAGIC_PARAM_CHARACTER.$key.PMQParam::LAST_MAGIC_PARAM_CHARACTER] = $this->convertMyDate($generatedParam);
        }

        return $paramGenerated;
    }

    public function formatNumberResult($results, $decimals=3, $dec_point=',', $thousands_sep='')
    {
        $countResults = count($results);
        for($i=0; $i<$countResults; $i++) {
            foreach($results[$i] as $j => $value) {
                if (is_numeric($value)) {
                    if((int)$value != $value) {
                        $results[$i][$j] = number_format($results[$i][$j], $decimals, $dec_point, $thousands_sep);
                    }
                }
            }
        }

        return $results;
    }

    public function executeQuery(PMQQuery $query, array $param=array(), $page=1)
    {
        $connection = $query->getConnection() != null ? $query->getConnection() : $this->getConnectionName();

        $return = $this->pmqQueryManager->execute(
                $query,
                $param,
                $connection,
                (($page*self::LIMIT_NB_RESULT) - self::LIMIT_NB_RESULT),
                self::LIMIT_NB_RESULT
                );

        if($return instanceof \PDOException) {
            return  array(
                'fieldList' => null,
                'resultList' => null,
                'count' => 0,
                'error' => $return->getMessage()
            );
        }

        $resultList = $return->fetchAll(\PDO::FETCH_ASSOC);
        $fieldList = array();
        if(count($resultList)>0) {
            $fieldList = array_keys($resultList[0]);
        }

        return array(
            'fieldList' => $fieldList,
            'resultList' => $this->formatNumberResult($resultList),
            'count' => $this->pmqQueryManager->getCount($connection)
        );
    }

    public function updateSetQuery($query, $time = null)
    {
        $query->setLastExecDt(new \DateTime());
        $query->setNbExec($query->getNbExec()+1);

        if ($time !== null) {

            if ($query->getAvgExecutionTime() === 0 && $query->getNbExec() > 1) {
                $query->setAvgExecutionTime($time * $query->getNbExec());
            }

            $avgExecutiontime = number_format(($time + $query->getAvgExecutionTime() * ($query->getNbExec() - 1)) / $query->getNbExec(), 2, '.', '');

            $query->setAvgExecutionTime($avgExecutiontime);
        }

        $this->pmqQueryManager->save($query);
    }

    public function addRunQueue($query, array $param=array(), $user, array $email=array())
    {
        $runQueue = new PMQRunQueue();
        $runQueue->setQuery($query);
//        $runQueue->setLfUser($user);
        $runQueue->setFirstRunDt($this->convertMyDate($param['firstRunDt'], null, 'Datetime'));
        $runQueue->setLastRunDt($this->convertMyDate($param['lastRunDt'], null, 'Datetime'));
        $runQueue->setRunExec($param['runExec']);
        $runQueue->setEmail(count($email)>0? implode(';', $email):$param['email']);
        $this->pmqRunQueueManager->save($runQueue);

        foreach($param['generatedParam'] as $key => $generatedParam) {
            $runQueueParam = new PMQRunQueueParam();
            $runQueueParam->setRunQueue($runQueue);

            $oParam = $this->pmqParamManager->loadBy(array('query' => $query->getIdQuery(), 'code' => $key));

            $runQueueParam->setParam($oParam[0]);

            $value = $this->convertMyDate($generatedParam);

            if (is_array($value)) {
                $value = serialize($value);
            }

            $runQueueParam->setValue($value);
            $this->pmqRunQueueParamManager->save($runQueueParam);
        }

        return $runQueue;
    }

    public function deleteRunQueue($idRunQueue)
    {
        $runQueue = $this->pmqRunQueueManager->load($idRunQueue);

        $this->pmqRunQueueManager->delete($runQueue);
    }

    public function deleteParam($param)
    {
        $this->pmqParamManager->delete($param);
    }

    public function deleteRight($right)
    {
        $this->pmqRightManager->delete($right);
    }

    public function convertMyDate($string, $separator='-', $outputType='')
    {
        if (is_array($string)) {
            return $string;
        }

        if($string == '') {
            return new \DateTime();
        }

        // Date with -
        $regexp = '/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/';
        if(preg_match("$regexp", $string)) {
            list($d, $m, $y) = explode('-', $string);
        }

        // Date with /
        $regexp = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
        if(preg_match("$regexp", $string)) {
            list($d, $m, $y) = explode('/', $string);
        }

        if(isset($d) && isset($m) && isset($y)) {
            if($outputType=='Datetime') {
                $date = new \DateTime();
                $date->setDate($y, $m, $d);

                return $date;
            }

            return $y.$separator.$m.$separator.$d;
        }

        return $string;
    }
}