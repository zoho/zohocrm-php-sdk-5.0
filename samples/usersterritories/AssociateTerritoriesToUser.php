<?php
namespace usersterritories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersterritories\APIException;
use com\zoho\crm\api\usersterritories\ActionWrapper;
use com\zoho\crm\api\usersterritories\BodyWrapper;
use com\zoho\crm\api\usersterritories\SuccessResponse;
use com\zoho\crm\api\usersterritories\Territory;
use com\zoho\crm\api\usersterritories\UsersTerritoriesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class AssociateTerritoriesToUser
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

    public static function associateTerritoriesToUser($userId)
    {
        $usersTerritoriesOperations = new UsersTerritoriesOperations();
        $request = new BodyWrapper();
        // List of User instances
        $userTerritoryList = [];
        $territory = new Territory();
        $territory->setId("3477061003051397");
        array_push($userTerritoryList, $territory);
        $request->setTerritories($userTerritoryList);
        // Call updateTerritoriesOfUser method that takes TerritoryBodyWrapper instance as parameter
        $response = $usersTerritoriesOperations->associateTerritoriesToUser($userId, $request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode());
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $responseWrapper = $actionHandler;
                $actionResponses = $responseWrapper->getTerritories();
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
$userId = "34770615791024";
AssociateTerritoriesToUser::initialize();
AssociateTerritoriesToUser::associateTerritoriesToUser($userId);
