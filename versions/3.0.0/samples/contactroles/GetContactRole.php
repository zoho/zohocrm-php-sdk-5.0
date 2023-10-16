<?php
namespace contactroles;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\contactroles\BodyWrapper;
use com\zoho\crm\api\contactroles\ContactRolesOperations;
use com\zoho\crm\api\contactroles\APIException;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetContactRole
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

    public static function getContactRole(string $contactRoleId)
    {
        $contactRolesOperations = new ContactRolesOperations();
        //Call getContactRole method that takes contactRoleId as parameter
        $response = $contactRolesOperations->getRole($contactRoleId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $contactRoles = $responseWrapper->getContactRoles();
                foreach ($contactRoles as $contactRole) {
                    echo ("ContactRole ID: " . $contactRole->getId() . "\n");
                    echo ("ContactRole Name: " . $contactRole->getName() . "\n");
                    echo ("ContactRole SequenceNumber: " . $contactRole->getSequenceNumber() . "\n");
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue());
                echo ("Code: " . $exception->getCode()->getValue());
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value);
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$contactRoleId="44024801334";
GetContactRole::initialize();
GetContactRole::getContactRole($contactRoleId);
