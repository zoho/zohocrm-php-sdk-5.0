<?php
namespace sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\ResponseWrapper;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetSharedRecordDetails
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
     * <h3> Get Shared Record Details </h3>
     * This method is used to get the details of a shared record and print the response.
     * @param moduleAPIName - The API Name of the module to get shared record details.
     * @param recordId - The ID of the record to be obtained.
     * @throws Exception
     */
    public static function getSharedRecordDetails(string $moduleAPIName, string $recordId)
    {
        //example
        //moduleAPIName = "module_api_name";
        //$recordId = "347002";
        $shareRecordsOperations = new ShareRecordsOperations($recordId, $moduleAPIName);
        $paramInstance = new ParameterMap();
        // $paramInstance->add(GetSharedRecordDetailsParam::view(), "summary");
        // $paramInstance->add(GetSharedRecordDetailsParam::sharedTo(), "34770615791024");
        //Call getSharedRecordDetails method that takes paramInstance as parameter
        $response = $shareRecordsOperations->getSharedRecordDetails($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $shareRecords = $responseWrapper->getShare();
                if ($shareRecords != null) {
                    foreach ($shareRecords as $shareRecord) {
                        echo ("ShareRecord ShareRelatedRecords: ");
                        print_r($shareRecord->getShareRelatedRecords());
                        echo ("\n");
                        $sharedThrough = $shareRecord->getSharedThrough();
                        if ($sharedThrough != null) {
                            echo ("RelatedRecord SharedThrough EntityName: " . $sharedThrough->getEntityName() . "\n");
                            $module = $sharedThrough->getModule();
                            if ($module != null) {
                                echo ("RelatedRecord SharedThrough Module ID: " . $module->getId() . "\n");
                                echo ("RelatedRecord SharedThrough Module Name: " . $module->getName() . "\n");
                            }
                            echo ("RelatedRecord SharedThrough ID: " . $sharedThrough->getId() . "\n");
                        }
                        echo ("ShareRecord SharedTime: ");
                        print_r($shareRecord->getSharedTime());
                        echo ("\n");
                        echo ("ShareRecord Permission: " . $shareRecord->getPermission()->getValue() . "\n");
                        $user = $shareRecord->getUser();
                        if ($user != null) {
                            echo ("ShareRecord User-ID: " . $user->getId() . "\n");
                            echo ("RelatedRecord User-FullName: " . $user->getFullName() . "\n");
                            echo ("RelatedRecord User-Zuid: " . $user->getZuid() . "\n");
                        }
                        $sharedBy = $shareRecord->getSharedBy();
                        if ($sharedBy != null) {
                            echo ("ShareRecord SharedBy User-ID: " . $sharedBy->getId() . "\n");
                            echo ("RelatedRecord SharedBy User-FullName: " . $sharedBy->getFullName() . "\n");
                            echo ("RelatedRecord SharedBy User-Zuid: " . $sharedBy->getZuid() . "\n");
                        }
                    }
                }
                if ($responseWrapper->getShareableUser() != null) {
                    $shareableUsers = $responseWrapper->getShareableUser();
                    foreach ($shareableUsers as $shareableUser) {
                        echo ("ShareRecord User-ID: " . $shareableUser->getId());
                        echo ("ShareRecord User-FullName: " . $shareableUser->getFullName());
                        echo ("ShareRecord User-Zuid: " . $shareableUser->getZuid());
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . ": " . $value . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
            }
        }
    }
}
$moduleAPIName="Leads";
$recordId = "34770615623115";
GetSharedRecordDetails::initialize();
GetSharedRecordDetails::getSharedRecordDetails($moduleAPIName,$recordId);
