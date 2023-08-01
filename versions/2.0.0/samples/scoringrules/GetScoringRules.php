<?php
namespace scoringrules;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\scoringrules\GetScoringRulesParam;
use com\zoho\crm\api\scoringrules\APIException;
use com\zoho\crm\api\scoringrules\ScoringRulesOperations;
use com\zoho\crm\api\scoringrules\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetScoringRules
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
    public static function getScoringRules()
    {
        $scoringRulesOperations = new ScoringRulesOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetScoringRulesParam::module(), "Leads");
        // Call getScoringRules method
        $response = $scoringRulesOperations->getScoringRules($paramInstance);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $scoringRules = $responseWrapper->getScoringRules();
                foreach ($scoringRules as $scoringRule) {
                    $layout = $scoringRule->getLayout();
                    if ($layout != null) {
                        echo ("ScoringRule Layout ID: " . $layout->getId() . "\n");
                        echo ("ScoringRule Layout APIName: " . $layout->getAPIName() . "\n");
                    }
                    echo ("ScoringRule CreatedTime: ");
                    print_r($scoringRule->getCreatedTime());
                    echo ("\n");
                    echo ("ScoringRule ModifiedTime: ");
                    print_r($scoringRule->getModifiedTime());
                    echo ("\n");
                    $fieldRules = $scoringRule->getFieldRules();
                    foreach ($fieldRules as $fieldRule) {
                        echo ("ScoringRule FieldRule Score: " . $fieldRule->getScore() . "\n");
                        $criteria = $fieldRule->getCriteria();
                        if ($criteria != null) {
                            self::printCriteria($criteria);
                        }
                        echo ("ScoringRule FieldRule Id: " . $fieldRule->getId() . "\n");
                    }
                    $module = $scoringRule->getModule();
                    if ($module != null) {
                        echo ("ScoringRule Module ID: " . $module->getId() . "\n");
                        echo ("ScoringRule Module APIName: " . $module->getAPIName() . "\n");
                    }
                    echo ("ScoringRule Name: " . $scoringRule->getName() . "\n");
                    $modifiedBy = $scoringRule->getModifiedBy();
                    if ($modifiedBy != null) {
                        echo ("ScoringRule Modified By Name : " . $modifiedBy->getName() . "\n");
                        echo ("ScoringRule Modified By id : " . $modifiedBy->getId() . "\n");
                    }
                    echo ("ScoringRule Active: " . $scoringRule->getActive() . "\n");
                    echo ("ScoringRule Description: " . $scoringRule->getDescription() . "\n");
                    echo ("ScoringRule Id: " . $scoringRule->getId() . "\n");
                    $signalRules = $scoringRule->getSignalRules();
                    if ($signalRules != null) {
                        foreach ($signalRules as $signalRule) {
                            echo ("ScoringRule SignalRule Score: " . $signalRule->getScore() . "\n");
                            echo ("ScoringRule SignalRule Id: " . $signalRule->getId() . "\n");
                            $signal = $signalRule->getSignal();
                            if ($signal != null) {
                                echo ("ScoringRule SignalRule Signal Namespace: " . $signal->getNamespace() . "\n");
                                echo ("ScoringRule SignalRule Signal Id: " . $signal->getId() . "\n");
                            }
                        }
                    }
                    $createdBy = $scoringRule->getCreatedBy();
                    if ($createdBy != null) {
                        echo ("ScoringRule Created By Name : " . $createdBy->getName() . "\n");
                        echo ("ScoringRule Created By id : " . $createdBy->getId() . "\n");
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    if ($info->getPerPage() != null) {
                        echo ("Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    if ($info->getCount() != null) {
                        echo ("Info Count: " . $info->getCount() . "\n");
                    }
                    if ($info->getPage() != null) {
                        echo ("Info Page: " . $info->getPage() . "\n");
                    }
                    if ($info->getMoreRecords() != null) {
                        echo ("Info MoreRecords: " . $info->getMoreRecords() . "\n");
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
    private static function printCriteria($criteria)
    {
        if ($criteria->getComparator() != null) {
            echo ("CustomView Criteria Comparator: " . $criteria->getComparator() . "\n");
        }
        if ($criteria->getField() != null) {
            echo ("CustomView Criteria field name: " . $criteria->getField()->getAPIName() . "\n");
        }
        if ($criteria->getValue() != null) {
            echo ("CustomView Criteria Value: " . $criteria->getValue() . "\n");
        }
        $criteriaGroup = $criteria->getGroup();
        if ($criteriaGroup != null) {
            foreach ($criteriaGroup as $criteria1) {
                self::printCriteria($criteria1);
            }
        }
        if ($criteria->getGroupOperator() != null) {
            echo ("CustomView Criteria Group Operator: " . $criteria->getGroupOperator() . "\n");
        }
    }
}
GetScoringRules::initialize();
GetScoringRules::getScoringRules();
