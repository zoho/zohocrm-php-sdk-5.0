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

class UpdateSharePermissions
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

    public static function updateSharePermissions(string $moduleAPIName, string $recordId)
    {
        $shareRecordsOperations = new ShareRecordsOperations($recordId, $moduleAPIName);
        $request = new BodyWrapper();
        //List of ShareRecord instances
        $shareList = array();
        $share1 = new ShareRecord();
        $share1->setShareRelatedRecords(true);
        $share1->setPermission(new Choice("full_access"));
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
        //Call updateSharePermissions method that takes BodyWrapper instance as parameter
        $response = $shareRecordsOperations->updateSharePermissions($request);
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
        }
    }
}
$moduleAPIName="Leads";
$recordId="34770615623115";
UpdateSharePermissions::initialize();
UpdateSharePermissions::updateSharePermissions($moduleAPIName,$recordId);
