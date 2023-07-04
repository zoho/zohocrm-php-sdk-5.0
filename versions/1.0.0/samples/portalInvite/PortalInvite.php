<?php
namespace portalInvite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\portalinvite\ActionWrapper;
use com\zoho\crm\api\portalinvite\APIException;
use com\zoho\crm\api\portalinvite\InviteUsersParam;
use com\zoho\crm\api\portalinvite\PortalInviteOperations;
use com\zoho\crm\api\portalinvite\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class PortalInvite
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
    public static function portalInvite($recordId, $module, $userTypeId, $type)
    {
        $portalinviteOperations = new PortalInviteOperations($module);
        $paraminstance = new ParameterMap();
        $paraminstance->add(InviteUsersParam::userTypeId(), $userTypeId);
        $paraminstance->add(InviteUsersParam::type(), $type);
        $paraminstance->add(InviteUsersParam::language(), "en_US");
        $response = $portalinviteOperations->inviteUsers($recordId, $paraminstance);
        if($response != null)
        {
            echo("Status Code: " . $response->getStatusCode());
            if($response->isExpected())
            {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper)
                {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getPortalInvite();
                    if ($actionResponses!= null)
                    {
                        foreach ($actionResponses as $actionResponse) {
                            if ($actionResponse instanceof SuccessResponse) {
                                $successResponse = $actionResponse;
                                echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                                echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                                echo("Details: ");
                                foreach ($successResponse->getDetails() as $key => $value) {
                                    echo($key . " : ");
                                    print_r($value);
                                    echo("\n");
                                }
                                echo("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                            }
                            else if ($actionResponse instanceof APIException) {
                                $exception = $actionResponse;
                                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                                echo("Code: " . $exception->getCode()->getValue() . "\n");
                                echo("Details: ");
                                foreach ($exception->getDetails() as $key => $value) {
                                    echo($key . " : " . $value . "\n");
                                }
                                echo("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                            }
                        }
                    }
                }
                elseif ($actionHandler instanceof APIException)
                {
                    $exception = $actionHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    echo("Details: ");
                    foreach ($exception->getDetails() as $key => $value)
                    {
                        echo($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
$recordId = "440248030088";
$module = "Contacts";
$userTypeId = "44024304019";
$type = "invite";
PortalInvite::initialize();
PortalInvite::portalInvite($recordId, $module, $userTypeId, $type);