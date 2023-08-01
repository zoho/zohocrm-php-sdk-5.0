<?php
namespace usersterritories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersterritories\APIException;
use com\zoho\crm\api\usersterritories\SuccessResponse;
use com\zoho\crm\api\usersterritories\TransferActionWrapper;
use com\zoho\crm\api\usersterritories\TransferAndDelink;
use com\zoho\crm\api\usersterritories\TransferToUser;
use com\zoho\crm\api\usersterritories\TransferWrapper;
use com\zoho\crm\api\usersterritories\UsersTerritoriesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DelinkAndTransferFromAllTerritories
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

    public static function delinkAndTransferFromAllTerritories($userId)
    {
        $usersTerritoriesOperations = new UsersTerritoriesOperations();
        $request = new TransferWrapper();
        // List of User instances
        $userTerritoryList = [];
        $territory = new TransferAndDelink();
        $territory->setId("3477061003051397");
        $transferToUser = new TransferToUser();
        $transferToUser->setId("3477061013767065");
        $territory->setTransferToUser($transferToUser);
        array_push($userTerritoryList, $territory);
        $request->setTransferAndDelink($userTerritoryList);
        // Call delinkAndTransferFromAllTerritories method that takes TransferBodyWrapper instance as parameter
        $response = $usersTerritoriesOperations->delinkAndTransferFromAllTerritories($userId, $request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof TransferActionWrapper) {
                $responseWrapper = $actionHandler;
                $actionResponses = $responseWrapper->getTransferAndDelink();
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
$userId="34770615791024";
DelinkAndTransferFromAllTerritories::initialize();
DelinkAndTransferFromAllTerritories::delinkAndTransferFromAllTerritories($userId);
