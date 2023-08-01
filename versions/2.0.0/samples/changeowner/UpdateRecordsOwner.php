<?php
namespace changeowner;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\changeowner\RelatedModules;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\changeowner\ChangeOwnerOperations;
use com\zoho\crm\api\changeowner\MassWrapper;
use com\zoho\crm\api\changeowner\Owner;
use com\zoho\crm\api\changeowner\APIException;
use com\zoho\crm\api\changeowner\SuccessResponse;
use com\zoho\crm\api\changeowner\ActionWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateRecordsOwner
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
    public static function updateRecordsOwner($moduleAPIName)
    {
        $changeOwnerOperations = new ChangeOwnerOperations($moduleAPIName);
        $massWrapper = new MassWrapper();
        // List of record id
        $Ids = array();
        array_push($Ids, "44024807154");
        array_push($Ids, "44024801182075");
        $massWrapper->setIds($Ids);
        $owner = new Owner();
        $owner->setId("440248254001");
        $massWrapper->setOwner($owner);
        $massWrapper->setNotify(true);
        $relatedModules = array();
        $relatedModule = new RelatedModules();
        $relatedModule->setId("440248001304060");
        $relatedModule->setAPIName("Tasks");
        array_push($relatedModules, $relatedModule);
        $relatedModule = new RelatedModules();
        $relatedModule->setId("3477061014686005");
        $relatedModule->setAPIName("Tasks");
        array_push($relatedModules, $relatedModule);
        $massWrapper->setRelatedModules($relatedModules);
        $response = $changeOwnerOperations->massUpdate($massWrapper);
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
                                echo ($key . " : ");
                                print_r($value);
                                echo ("\n");
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo ("Code: " . $exception->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            if ($exception->getDetails() != null) {
                                foreach ($exception->getDetails() as $key => $value) {
                                    echo ($key . ": " . $value . "\n");
                                }
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
                    if ($exception->getDetails() != null) {
                        foreach ($exception->getDetails() as $key => $value) {
                            echo ($key . ": " . $value . "\n");
                        }
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
$moduleAPIName="Leads";
UpdateRecordsOwner::initialize();
UpdateRecordsOwner::updateRecordsOwner($moduleAPIName);
