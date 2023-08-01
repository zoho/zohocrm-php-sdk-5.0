<?php
namespace masschangeowner;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\masschangeowner\BodyWrapper;
use com\zoho\crm\api\masschangeowner\APIException;
use com\zoho\crm\api\masschangeowner\ActionWrapper;
use com\zoho\crm\api\masschangeowner\MassChangeOwnerOperations;
use com\zoho\crm\api\masschangeowner\Owner;
use com\zoho\crm\api\masschangeowner\SuccessResponse;
use com\zoho\crm\api\masschangeowner\Territory;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class ChangeOwner
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
    public static function changeOwner($moduleAPIName)
    {
        $massChangeOwnerOperations = new MassChangeOwnerOperations();
        $bodyWrapper = new BodyWrapper();
        $bodyWrapper->setCvid("440248029342");
        $owner = new Owner();
        $owner->setId("440248254001");
        $bodyWrapper->setOwner($owner);
        $territory = new Territory();
        $territory->setId("3652397007622003");
        $territory->setIncludeChild(true);
        $bodyWrapper->setTerritory($territory);
        $response = $massChangeOwnerOperations->changeOwner($moduleAPIName, $bodyWrapper);
        if ($response != null) {
            echo ("Status code" . $response->getStatusCode() . "\n");
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
                        if ($successResponse->getDetails() != null) {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue) {
                                echo ($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    }
                    else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
                        echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo ("Code: " . $exception->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        if ($exception->getDetails() != null) {
                            foreach ($exception->getDetails() as $keyName => $keyValue) {
                                echo ($keyName . ": " . $keyValue . "\n");
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
                if ($exception->getDetails() != null) {
                    echo ("Details: \n");
                    foreach ($exception->getDetails() as $keyName => $keyValue) {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName="leads";
ChangeOwner::initialize();
ChangeOwner::changeOwner($moduleAPIName);
