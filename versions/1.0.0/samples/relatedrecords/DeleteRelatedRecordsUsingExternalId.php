<?php
namespace relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\ActionWrapper;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\DelinkRecordsParam;
use com\zoho\crm\api\relatedrecords\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DeleteRelatedRecordsUsingExternalId
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
     * <h3> Delete RelatedRecords Using External Id </h3>
     * This method is used to delete the association between modules and print the response.
     * @param moduleAPIName - The API Name of the module to delink related records.
     * @param externalValue -
     * @param relatedListAPIName - The API name of the related list. To get the API name of the related list.
     * @param relatedListIds - The ID of the related record.
     * @throws Exception
     */
    public static function deleteRelatedRecordsUsingExternalId(string $moduleAPIName, string $externalValue, string $relatedListAPIName, array $relatedListIds)
    {
        //API Name of the module to update record
        //moduleAPIName = "module_api_name";
        //$externalValue = "34770615177002";
        //$relatedListAPIName = "module_api_name";
        $xExternal = "Leads.External,Products.Products_External";
        $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName, $xExternal);
        $paramInstance = new ParameterMap();
        foreach ($relatedListIds as $relatedListId) {
            $paramInstance->add(DelinkRecordsParam::ids(), $relatedListId);
        }
        //Call deleteRelatedRecordsUsingExternalId method that takes externalValue and paramInstance instance as parameter.
        $response = $relatedRecordsOperations->deleteRelatedRecordsUsingExternalId($externalValue, $paramInstance);
        if ($response != null) {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                            echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                            echo("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo($key . " : " . $value . "\n");
                            }
                echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        } else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo("Code: " . $exception->getCode()->getValue() . "\n");
                            echo("Details: ");
                            foreach ($exception->getDetails() as $key => $value) {
                                echo($key . " : " . $value . "\n");
                            }
                            echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                        }
                    }
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    echo("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo($key . " : " . $value . "\n");
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
$externalValue = "External123";
$relatedListAPIName = "Products";
$relatedListIds=array("32345323231321","32345323231321");
DeleteRelatedRecordsUsingExternalId::initialize();
DeleteRelatedRecordsUsingExternalId::deleteRelatedRecordsUsingExternalId($moduleAPIName,$externalValue,$relatedListAPIName,$relatedListIds);
