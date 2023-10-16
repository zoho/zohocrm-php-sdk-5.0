<?php
namespace usertypeusers;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usertypeusers\APIException;
use com\zoho\crm\api\usertypeusers\ResponseWrapper;
use com\zoho\crm\api\usertypeusers\Users;
use com\zoho\crm\api\usertypeusers\UserTypeUsersOperations;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class GetUsersOfUserType
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

    public static function getUsersOfUserType($portalName, $userTypeId)
    {
        $userTypeUsersOperations = new UserTypeUsersOperations();
        $paramInstance = new ParameterMap(); 
        $response = $userTypeUsersOperations->getUsersOfUserType($userTypeId, $portalName, $paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrappr = $responseHandler;
                    $users = $responseWrappr->getUsers();
                    foreach ($users as $user) {
                        if ($user instanceof Users) {
                            echo("Users PersonalityId: " . $user->getPersonalityId() . "\n");
                            echo("users Confirm: " . $user->getConfirm() . "\n");
                            echo("Users StatusReasonS: " . $user->getStatusReasonS() . "\n");
                            echo("users InvitedTime: " . date_format($user->getInvitedTime(), "d-m-Y-H-i-s") . "\n");
                            echo("Users Module: " . $user->getModule() . "\n");
                            echo("Users Name: " . $user->getName() . "\n");
                            echo("Users Active: " . $user->getActive() . "\n");
                            echo("Users Email: " . $user->getEmail() . "\n");
                        }
                    }
                    $info = $responseWrappr->getInfo();
                    if ($info != null) {
                        if ($info->getPerPage() != null) {
                            echo("Users Info PerPage: " . strval($info->getPerPage()) . "\n");
                        }
                        if ($info->getCount() != null) {
                            echo("Users Info Count: " . strval($info->getCount()) . "\n");
                        }
                        if ($info->getPage() != null) {
                            echo("Users Info Page: " . strval($info->getPage()) . "\n");
                        }
                        if ($info->getMoreRecords() != null) {
                            echo("Users INfo MoreRecords: " . strval($info->getMoreRecords()) . "\n");
                        }
                    }
                }
                else if ($responseHandler instanceof APIException)
                {
                    $exception = $responseHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value)
                    {
                        echo ($key . ": " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
$portalName = "PortalsAPItest101";
$userTypeId = 347706117221008;
GetUsersOfUserType::initialize();
GetUsersOfUserType::getUsersOfUserType($portalName, $userTypeId);
