<?php
namespace massdeletecvid;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\massdeletecvid\APIException;
use com\zoho\crm\api\massdeletecvid\MassDeleteCvidOperations;
use com\zoho\crm\api\massdeletecvid\GetMassDeleteStatusParam;
use com\zoho\crm\api\massdeletecvid\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetMassDeleteStatus
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
    public static function getMassDeleteStatus(string $jobId, string $moduleAPIName)
    {
        $massDeleteCvidOperations = new MassDeleteCvidOperations($moduleAPIName);
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetMassDeleteStatusParam::jobId(), $jobId);
        $response = $massDeleteCvidOperations->getMassDeleteStatus($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $status = $responseWrapper->getData();
                foreach ($status as $status1) {
                    echo ("MassDelete TotalCount: " .  $status1->getTotalCount() . "\n");
                    echo ("MassDelete ConvertedCount: " .  $status1->getDeletedCount() . "\n");
                    echo ("MassDelete FailedCount: " .  $status1->getFailedCount() . "\n");
                    echo ("MassDelete Status: " .  $status1->getStatus()->getValue() . "\n");
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
}
$jobId="302321234003";
$moduleAPIName="leads";
GetMassDeleteStatus::initialize();
GetMassDeleteStatus::getMassDeleteStatus($jobId,$moduleAPIName);
