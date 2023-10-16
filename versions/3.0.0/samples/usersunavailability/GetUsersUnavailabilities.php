<?php
namespace usersunavailability;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersunavailability\APIException;
use com\zoho\crm\api\usersunavailability\ResponseWrapper;
use com\zoho\crm\api\usersunavailability\UsersUnavailabilityOperations;
use com\zoho\crm\api\usersunavailability\GetUsersUnavailabilityParam;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetUsersUnavailabilities
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
    public static function getUsersUnavailabilities()
    {
        $usersUnavailabilityOperations = new UsersUnavailabilityOperations();
        $paramInstance = new ParameterMap();
        // $paramInstance->add(GetUsersUnavailabilityParam::groupIds(), "34770619,3477061912");
        // $paramInstance->add(GetUsersUnavailabilityParam::includeInnerDetails(), "56xxx8");
        // $paramInstance->add(GetUsersUnavailabilityParam::roleIds(), "343370619,3403706191");
        // $paramInstance->add(GetUsersUnavailabilityParam::territoryIds(), "343370619,3403706191");
        $filters = [];
        $filters["group_operator"] = "or";
        $group = [];
        $criteria1 = [];
        $criteria1["comparator"] = "between";
        $criteria1Field = [];
        $criteria1Field["api_name"] = "from";
        $criteria1["field"] = $criteria1Field;
        $criteria1Value = ["2021-02-18T19:00:00+05:30", "2021-02-19T19:00:00+05:30"];
        $criteria1["value"] = $criteria1Value;
        array_push($group, $criteria1);
        $criteria2 = [];
        $criteria2["comparator"] = "between";
        $criteria2Field = [];
        $criteria2Field["api_name"] = "to";
        $criteria2["field"] = $criteria2Field;
        $criteria2Value = ["2021-02-18T20:00:00+05:30", "2021-02-19T20:00:00+05:30"];
        $criteria2["value"] = $criteria2Value;
        array_push($group, $criteria2);
        $filters["group"] = $group;
        $paramInstance->add(GetUsersUnavailabilityParam::filters(), json_encode($filters, JSON_UNESCAPED_UNICODE));
        // Call getUsersUnavailabilityHours method that takes ParameterMap instance as parameters
        $response = $usersUnavailabilityOperations->getUsersUnavailability($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $users = $responseWrapper->getUsersUnavailability();
                foreach ($users as $usersUnavailability) {
                    echo ("UsersUnavailability Comments: " . $usersUnavailability->getComments() . "\n");
                    echo ("UsersUnavailability From: ");
                    print_r($usersUnavailability->getFrom());
                    echo ("\n");
                    echo ("UsersUnavailability Id: " . $usersUnavailability->getId() . "\n");
                    echo ("UsersUnavailability to: ");
                    print_r($usersUnavailability->getTo());
                    echo ("\n");
                    $user = $usersUnavailability->getUser();
                    if ($user != null) {
                        echo ("UsersUnavailability User-Name: " . $user->getName() . "\n");
                        echo ("UsersUnavailability User-Id: " . $user->getId() . "\n");
                        echo ("UsersUnavailability User-ZuId: " . $user->getZuid() . "\n");
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    if ($info->getPerPage() != null) {
                        echo ("User Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    if ($info->getCount() != null) {
                        echo ("User Info Count: " . $info->getCount() . "\n");
                    }
                    if ($info->getPage() != null) {
                        echo ("User Info Page: " . $info->getPage() . "\n");
                    }
                    if ($info->getMoreRecords() != null) {
                        echo ("User Info MoreRecords: " . $info->getMoreRecords() . "\n");
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
GetUsersUnavailabilities::initialize();
GetUsersUnavailabilities::getUsersUnavailabilities();
