<?php
namespace territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\ResponseWrapper;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\territories\Criteria;
use com\zoho\crm\api\territories\GetChildTerritoryParam;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class GetChildTerritory
{
    public static function initialize()
    {
        $environment = USDataCenter::PRODUCTION();
        $token = (new OAuthBuilder())
            ->clientId("client_id")
            ->clientSecret("client_secret")
            ->refreshToken("refresh_token")
            ->build();
        (new InitializeBuilder())
            ->environment($environment)
            ->token($token)
            ->initialize();
    }

    public static function getChildTerritory($id)
    {
        $territoriesOperations = new TerritoriesOperations();
        $paraminstance = new ParameterMap();
        // $paraminstance->add(GetChildTerritoryParam::filters(), "");
        $response = $territoriesOperations->getChildTerritory($id, $paraminstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $territoryList = $responseWrapper->getTerritories();
                if ($territoryList != null) {
                    foreach ($territoryList as $territory) {
                        echo ("Territory CreatedTime: ");
                        print_r($territory->getCreatedTime());
                        echo ("\n");
                        echo ("Territory PermissionType: " . $territory->getPermissionType()->getValue() . "\n");
                        echo ("Territory ModifiedTime: ");
                        print_r($territory->getModifiedTime());
                        echo ("\n");
                        $manager = $territory->getManager();
                        if ($manager != null) {
                            echo ("Territory Manager User-Name: " . $manager->getName() . "\n");
                            echo ("Territory Manager User-ID: " . $manager->getId() . "\n");
                        }
                        $criteria = $territory->getAccountRuleCriteria();
                        if ($criteria != null) {
                            self::printCriteria($criteria);
                        }
                        echo ("Territory Name: " . $territory->getName() . "\n");
                        $modifiedBy = $territory->getModifiedBy();
                        if ($modifiedBy != null) {
                            echo ("Territory Modified By User-Name: " . $modifiedBy->getName() . "\n");
                            echo ("Territory Modified By User-ID: " . $modifiedBy->getId() . "\n");
                        }
                        echo ("Territory Description: " . $territory->getDescription() . "\n");
                        echo ("Territory ID: " . $territory->getId() . "\n");
                        $reportingTo = $territory->getReportingTo();
                        if ($reportingTo != null) {
                            echo ("Territory ReportingTo User-Name: " . $reportingTo->getName() . "\n");
                            echo ("Territory ReportingTo User-ID: " . $reportingTo->getId() . "\n");
                        }
                        $dealcriteria = $territory->getDealRuleCriteria();
                        if ($dealcriteria != null) {
                            self::printCriteria($dealcriteria);
                        }
                        $createdBy = $territory->getCreatedBy();
                        if ($createdBy != null) {
                            echo ("Territory Created By User-Name: " . $createdBy->getName() . "\n");
                            echo ("Territory Created By User-ID: " . $createdBy->getId() . "\n");
                        }
                    }
                }
                $info = $responseWrapper->getInfo();
                echo ("Territory Info PerPage : " . $info->getPerPage() . "\n");
                echo ("Territory Info Count : " . $info->getCount() . "\n");
                echo ("Territory Info Page : " . $info->getPage() . "\n");
                echo ("Territory Info MoreRecords : ");
                print_r($info->getMoreRecords());
                echo ("\n");
            } else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . " : " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
    private static function printCriteria($criteria)
    {
        if ($criteria instanceof Criteria) {
            if ($criteria->getComparator() != null) {
                echo ("Territory Criteria Comparator: " . $criteria->getComparator() . "\n");
            }
            $field = $criteria->getField();
            if ($field != null) {
                echo ("Territory Criteria Field: " . $field->getAPIName() . "\n");
                echo ("Territory Criteria Field: " . $field->getId() . "\n");
            }
            echo ("Territory Criteria Value: ");
            print_r($criteria->getValue());
            echo ("\n");
            $criteriaGroup = $criteria->getGroup();
            if ($criteriaGroup != null) {
                foreach ($criteriaGroup as $criteria1) {
                    self::printCriteria($criteria1);
                }
            }
            if ($criteria->getGroupOperator() != null) {
                echo ("Territory Criteria Group Operator: " . $criteria->getGroupOperator() . "\n");
            }
        }
    }
}

$id = "34770613051397";
GetChildTerritory::initialize();
GetChildTerritory::getChildTerritory($id);
