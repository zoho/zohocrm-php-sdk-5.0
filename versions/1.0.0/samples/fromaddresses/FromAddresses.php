<?php
namespace fromaddresses;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\fromaddresses\APIException;
use com\zoho\crm\api\fromaddresses\FromAddressesOperations;
use com\zoho\crm\api\fromaddresses\ResponseWrapper;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class FromAddresses
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
    public static function getEmailAddresses()
    {
        $sendMailsOperations = new FromAddressesOperations();
        $response = $sendMailsOperations->getFromAddresses();
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $userAddresses = $responseWrapper->getFromAddresses();
                foreach ($userAddresses as $userAddress) {
                    echo ("UserAddress Email: " . $userAddress->getEmail() . "\n");
                    echo ("UserAddress Type: " . $userAddress->getType() . "\n");
                    echo ("UserAddress UserName: " . $userAddress->getUserName() . "\n");
                    echo ("UserAddress Default: " . $userAddress->getDefault() . "\n");
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
FromAddresses::initialize();
FromAddresses::getEmailAddresses();