<?php
namespace organization;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\org\APIException;
use com\zoho\crm\api\org\OrgOperations;
use com\zoho\crm\api\org\SuccessResponse;
use com\zoho\crm\api\org\FileBodyWrapper;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\util\StreamWrapper;

require_once "vendor/autoload.php";

class UploadOrganizationPhoto
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

    public static function uploadOrganizationPhoto(string $absoluteFilePath)
    {
        $orgOperations = new OrgOperations();
        $fileBodyWrapper = new FileBodyWrapper();
        $streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);
        $fileBodyWrapper->setFile($streamWrapper);
        $response = $orgOperations->uploadOrganizationPhoto($fileBodyWrapper);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if ($actionResponse instanceof SuccessResponse) {
                $successResponse = $actionResponse;
                echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo ("Details: ");
                if ($successResponse->getDetails() != null) {
                    foreach ($successResponse->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                }
                echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
            }
            else if ($actionResponse instanceof APIException) {
                $exception = $actionResponse;
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
$absoluteFilePath="users/user_name/docs";
UploadOrganizationPhoto::initialize();
UploadOrganizationPhoto::uploadOrganizationPhoto($absoluteFilePath);
