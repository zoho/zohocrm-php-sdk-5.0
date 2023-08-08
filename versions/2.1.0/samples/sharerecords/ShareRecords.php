<?php
namespace sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\ActionWrapper;
use com\zoho\crm\api\sharerecords\BodyWrapper;
use com\zoho\crm\api\sharerecords\ShareRecord;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\sharerecords\SuccessResponse;
use com\zoho\crm\api\users\Users;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class ShareRecords
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
     * <h3> Share Record </h3>
     * This method is used to share the record and print the response.
     * @param moduleAPIName - The API Name of the module to shared record.
     * @param recordId - The ID of the record to be obtained.
     * @throws Exception
     */
    public static function shareRecord(string $moduleAPIName, string $recordId)
    {
        //example
        //moduleAPIName = "module_api_name";
        //$recordId = "3477002";
        $shareRecordsOperations = new ShareRecordsOperations($recordId, $moduleAPIName);
        $request = new BodyWrapper();
        //List of ShareRecord instances
        $shareList = array();
        for ($i = 0; $i < 1; $i++) {
            $share1 = new ShareRecord();
            $share1->setShareRelatedRecords(true);
            $share1->setPermission(new Choice("read_write"));
            $user = new Users();
            $user->setId("3477061173021");
            $share1->setUser($user);
            $sharedWith = new Users();
            $sharedWith->setId("34770615791024");
            $sharedWith->addKeyValue("type", "users");
            $share1->setSharedWith($sharedWith);
            $share1->setType(new Choice("private"));
            array_push($shareList, $share1);
        }
        $share1 = new ShareRecord();
        $share1->setShareRelatedRecords(true);
        $share1->setPermission(new Choice("read_write"));
        $user = new Users();
        $user->setId("34770615791024");
        $share1->setUser($user);
        $sharedWith = new Users();
        $sharedWith->setId("34770615791024");
        $sharedWith->addKeyValue("type", "users");
        $share1->setSharedWith($sharedWith);
        $share1->setType(new Choice("private"));
        array_push($shareList, $share1);
        $request->setShare($shareList);
        //Call getSharedRecordDetails method that takes paramInstance as parameter
        $response = $shareRecordsOperations->shareRecord($request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getShare();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof SuccessResponse) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        if ($successResponse->getDetails() != null) {
                            echo ("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : " . $value . "\n");
                            }
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    }
                    else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
                        echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo ("Code: " . $exception->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($exception->getDetails() as $key => $value) {
                            echo ($key . " : ");
                            print_r($value);
                            echo ("\n");
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
        }
    }
}
$moduleAPIName="Leads";
$recordId="34770615623115";
ShareRecords::initialize();
ShareRecords::shareRecord($moduleAPIName,$recordId);
