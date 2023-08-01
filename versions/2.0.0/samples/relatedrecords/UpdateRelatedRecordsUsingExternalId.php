<?php
namespace relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\ActionWrapper;
use com\zoho\crm\api\relatedrecords\BodyWrapper;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\SuccessResponse;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateRelatedRecordsUsingExternalId
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
     * <h3> Update Related Records Using External Id</h3>
     * This method is used to update the relation between the records and print the response.
     * @param moduleAPIName - The API Name of the module to update related records.
     * @param externalValue -
     * @param relatedListAPIName - The API name of the related list. To get the API name of the related list.
     * @throws Exception
     */
    public static function updateRelatedRecordsUsingExternalId(string $moduleAPIName, string $externalValue, string $relatedListAPIName)
    {
        //API Name of the module to update record
        //moduleAPIName = "module_api_name";
        //$externalValue = "3477002";
        //$relatedListAPIName = "module_api_name";
        $xExternal = "Leads.External,Products.Products_External";
        $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName, $xExternal);
        $request = new BodyWrapper();
        //List of Record instances
        $records = array();
        $record1 = new Record();
        /*
         * Call addKeyValue method that takes two arguments
         * 1 -> A string that is the Field's API Name
         * 2 -> Value
         */
        $record1->addKeyValue("Products_External", "Products_External");
        $record1->addKeyValue("list_price", 50.56);
        //Add Record instance to the list
        array_push($records, $record1);
        $record2 = new Record();
        /*
         * Call addKeyValue method that takes two arguments
         * 1 -> A string that is the Field's API Name
         * 2 -> Value
         */
        $record2->addKeyValue("Products_External", "AutomatedSDKExternal");
        $record2->addKeyValue("list_price", 50.56);
        //Add Record instance to the list
        array_push($records, $record2);
        $request->setData($records);
        //Call updateRelatedRecordsUsingExternalId method that takes externalValue and BodyWrapper instance as parameter.
        $response = $relatedRecordsOperations->updateRelatedRecordsUsingExternalId($externalValue, $request);
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
$externalValue = "3477061005177002";
$relatedListAPIName = "Products";
UpdateRelatedRecordsUsingExternalId::initialize();
UpdateRelatedRecordsUsingExternalId::updateRelatedRecordsUsingExternalId($moduleAPIName,$externalValue,$relatedListAPIName);
