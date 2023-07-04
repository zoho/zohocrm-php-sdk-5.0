<?php
namespace usersunavailability;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersunavailability\APIException;
use com\zoho\crm\api\usersunavailability\ActionWrapper;
use com\zoho\crm\api\usersunavailability\BodyWrapper;
use com\zoho\crm\api\usersunavailability\SuccessResponse;
use com\zoho\crm\api\usersunavailability\UsersUnavailabilityOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateUsersUnavailability
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

    public static function updateUsersUnavailability($id)
    {
        $usersOperations = new UsersUnavailabilityOperations();
        $request = new BodyWrapper();
        // List of User instances
        $userList = [];
        $usersUnavailabilityClass = 'com\zoho\crm\api\usersunavailability\UsersUnavailability';
        $user1 = new $usersUnavailabilityClass();
        $user1->setComments("Unavailable");
        $user1->setId('3477061015179001');
        $from = date_create("2022-04-29T15:10:00");
        $user1->setFrom($from);
        $to = date_create("2022-05-29T15:10:00");
        $user1->setTo($to);
        array_push($userList, $user1);
        $request->setUsersUnavailability($userList);
        // Call updateUsersUnavailability method that takes id and BodyWrapper class instance as parameter
        $response = $usersOperations->updateUserUnavailability($id, $request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $responseWrapper = $actionHandler;
                $actionResponses = $responseWrapper->getUsersUnavailability();
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
$id="347706118952001";
UpdateUsersUnavailability::initialize();
UpdateUsersUnavailability::updateUsersUnavailability($id);
