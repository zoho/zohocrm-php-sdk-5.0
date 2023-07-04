<?php
namespace sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\DeleteActionWrapper;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\sharerecords\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class RevokeSharedRecord
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
    /**
     * <h3> Revoke Shared Record </h3>
     * This method is used to revoke access to a shared record that was shared to users and print the response.
     * @param moduleAPIName - The API Name of the module to update share permissions.
     * @param recordId - The ID of the record to be obtained.
     * @throws Exception
     */
    public static function revokeSharedRecord(string $moduleAPIName, string $recordId)
    {
        //example
        //moduleAPIName = "Leads";
        //recordId = "34770615177002";
        $shareRecordsOperations = new ShareRecordsOperations($recordId, $moduleAPIName);
        //Call revokeSharedRecord method
        $response = $shareRecordsOperations->revokeSharedRecord();
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $deleteActionHandler = $response->getObject();
            if ($deleteActionHandler instanceof DeleteActionWrapper) {
                $deleteActionWrapper = $deleteActionHandler;
                $deleteActionResponse = $deleteActionWrapper->getShare();
                if ($deleteActionResponse instanceof SuccessResponse) {
                    $successResponse = $deleteActionResponse;
                    echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                    echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($successResponse->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                }
                else if ($deleteActionResponse instanceof APIException) {
                    $exception = $deleteActionResponse;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            }
            else if ($deleteActionHandler instanceof APIException) {
                $exception = $deleteActionHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
            }
        }
    }
}
$moduleAPIName="Leads";
$recordId="34770615623115";
RevokeSharedRecord::initialize();;
RevokeSharedRecord::revokeSharedRecord($moduleAPIName,$recordId);
