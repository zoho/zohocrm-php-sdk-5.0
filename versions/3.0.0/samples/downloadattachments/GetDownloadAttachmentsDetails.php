<?php
namespace downloadattachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\downloadattachments\APIException;
use com\zoho\crm\api\downloadattachments\DownloadAttachmentsOperations;
use com\zoho\crm\api\downloadattachments\FileBodyWrapper;
use com\zoho\crm\api\downloadattachments\GetDownloadAttachmentsDetailsParam;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetDownloadAttachmentsDetails
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
    public static function getDownloadAttachmentsDetails($module, $recordId, $userId, $messageId, $destinationFolder)
    {
        $downloadAttachmentsOperations = new DownloadAttachmentsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetDownloadAttachmentsDetailsParam::messageId(), $messageId);
        $paramInstance->add(GetDownloadAttachmentsDetailsParam::userId(),$userId);
        $response = $downloadAttachmentsOperations->getDownloadAttachmentsDetails($recordId, $module, $paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if ($response->getStatusCode() == 204) {
                echo ("No Content\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof FileBodyWrapper) {
                $fileBodyWrapper = $responseHandler;
                $streamWrapper = $fileBodyWrapper->getFile();
                $fp = fopen($destinationFolder . "/" . $streamWrapper->getName(), "w");
                $stream = $streamWrapper->getStream();
                fputs($fp, $stream);
                fclose($fp);
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
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
$recordId ="44024800774074";
$module = "leads";
$userId = "44024800254001";
$messageId = "c6085fae00ffefab46b2ddb74aec507b3311e64ad57c672d523";
$destinationFolder = "/PHP/php-sdk-sample/file";
GetDownloadAttachmentsDetails::initialize();
GetDownloadAttachmentsDetails::getDownloadAttachmentsDetails($module, $recordId, $userId, $messageId, $destinationFolder);
