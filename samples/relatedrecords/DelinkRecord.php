<?php
namespace relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\ActionWrapper;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DelinkRecord
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
     * <h3> Delink Record </h3>
     * This method is used to delete the association between modules and print the response.
     * @param moduleAPIName - The API Name of the module to delink related record.
     * @param recordId - The ID of the record to be obtained.
     * @param relatedListAPIName - The API name of the related list. To get the API name of the related list.
     * @param relatedListId - The ID of the related record.
     * @throws Exception
     */
    public static function delinkRecord(string $moduleAPIName, string $recordId, string $relatedListAPIName, string $relatedListId)
    {
        //API Name of the module to update record
        //$moduleAPIName = "module_api_name";
        //$recordId = "34770615177002";
        //$relatedListAPIName = "module_api_name";
        //$relatedRecordId = "34770614994115";
        $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName);
        //Call updateRecord method that takes relatedListId as parameter.
        $response = $relatedRecordsOperations->delinkRecord($relatedListId, $recordId);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                            echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : " . $value . "\n");
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo ("Code: " . $exception->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            foreach ($exception->getDetails() as $key => $value) {
                                echo ($key . " : " . $value . "\n");
                            }
                            echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                        }
                    }
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            } else {
                print_r($response);
            }
        }
    }
}
$moduleAPIName = "Leads";
$recordId = "440248774074";
$relatedListAPIName = "notes";
$relatedRecordId = "440248001055023";
DelinkRecord::initialize();
DelinkRecord::delinkRecord($moduleAPIName,$recordId,$relatedListAPIName,$relatedRecordId);
