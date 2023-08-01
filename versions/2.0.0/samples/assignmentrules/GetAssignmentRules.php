<?php
namespace assignmentrules;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\assignmentrules\AssignmentRulesOperations;
use com\zoho\crm\api\assignmentrules\GetAssignmentRulesParam;
use com\zoho\crm\api\assignmentrules\ResponseWrapper;
use com\zoho\crm\api\assignmentrules\APIException;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetAssignmentRules
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
    public static function getAssignmentRules(string $moduleAPIName)
    {
        $assignmentRulesOperations = new AssignmentRulesOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetAssignmentRulesParam::module(), $moduleAPIName);
        $response = $assignmentRulesOperations->getAssignmentRules($paramInstance);
        if ($response != null)
        {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304)))
            {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $assignmentRules = $responseWrapper->getAssignmentRules();
                foreach ($assignmentRules as $assignmentRule)
                {
                    echo ("AssignmentRule ID : " . $assignmentRule->getId() . "\n");
                    echo ("AssignmentRule Name : " . $assignmentRule->getName() . "\n");
                    echo ("AssignmentRule ModifiedTime : ");
                    print_r($assignmentRule->getModifiedTime());
                    echo ("AssignmentRule CreatedTime : ");
                    print_r($assignmentRule->getCreatedTime());
                    $defaultAssignee = $assignmentRule->getDefaultAssignee();
                    if ($defaultAssignee != null)
                    {
                        echo ("AssignmentRule DefaultAssignee Id : " . $defaultAssignee->getId() . "\n");
                        echo ("AssignmentRule DefaultAssignee Name : " . $defaultAssignee->getName() . "\n");
                    }
                    $module = $assignmentRule->getModule();
                    if ($module != null)
                    {
                        echo ("AssignmentRule Module Id : " . $module->getId() . "\n");
                        echo ("AssignmentRule Module APIName : " . $module->getAPIName() . "\n");
                    }
                    $modifiedBy = $assignmentRule->getModifiedBy();
                    if ($modifiedBy != null)
                    {
                        echo ("AssignmentRule ModifiedBy Id : " . $modifiedBy->getId() . "\n");
                        echo ("AssignmentRule ModifiedBy Name : " . $modifiedBy->getName() . "\n");
                    }
                    echo ("AssignmentRule Description : " . $assignmentRule->getDescription() . "\n");
                    $createdBy = $assignmentRule->getCreatedBy();
                    if ($createdBy != null)
                    {
                        echo ("AssignmentRule CreatedBy Id : " . $createdBy->getId() . "\n");
                        echo ("AssignmentRule CreatedBy Name : " . $createdBy->getName() . "\n");
                    }
                }
            }
            else if ($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo ("Status : " . $exception->getStatus()->getValue());
                echo ("Code : " . $exception->getCode()->getValue());
                echo ("Details : ");
                foreach ($exception->getDetails() as $key => $value)
                {
                    echo ($key . " : " . $value);
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName = "leads";
GetAssignmentRules::initialize();
GetAssignmentRules::getAssignmentRules($moduleAPIName);