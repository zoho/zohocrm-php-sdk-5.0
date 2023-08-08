<?php
namespace masschangeowner;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\masschangeowner\APIException;
use com\zoho\crm\api\masschangeowner\MassChangeOwnerOperations;
use com\zoho\crm\api\masschangeowner\CheckStatusParam;
use com\zoho\crm\api\masschangeowner\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class CheckStatus
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
    public static function checkStatus(string $jobId, string $module)
    {
        $massChangeOwnerOperations = new MassChangeOwnerOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(CheckStatusParam::jobId(), $jobId);
        $response = $massChangeOwnerOperations->checkStatus($module, $paramInstance);
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
                    echo ("MassChangeOwner TotalCount: " . $status1->getTotalCount());
                    echo ("MassChangeOwner UpdatedCount: " . $status1->getUpdatedCount());
                    echo ("MassChangeOwner NotUpdatedCount: " . $status1->getNotUpdatedCount());
                    echo ("MassChangeOwner FailedCount: " . $status1->getFailedCount());
                    echo ("MassChangeOwner Status: " . $status1->getStatus());
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue());
                echo ("Code: " . $exception->getCode()->getValue());
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value);
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$jobId="4402401333549";
$module="leads";
CheckStatus::initialize();
CheckStatus::checkStatus($jobId,$module);
