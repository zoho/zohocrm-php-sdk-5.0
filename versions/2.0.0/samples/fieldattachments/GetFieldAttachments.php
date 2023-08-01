<?php
namespace fieldattachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\fieldattachments\FieldAttachmentsOperations;
use com\zoho\crm\api\fieldattachments\FileBodyWrapper;
use com\zoho\crm\api\fieldattachments\APIException;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetFieldAttachments
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
    public static function getFieldAttachments(string $moduleAPIName, string $recordId, $fieldsAttachmentId = null, $destinationFolder = null)
    {
        $fieldAttachmentsOperations = new FieldAttachmentsOperations($moduleAPIName, $recordId, $fieldsAttachmentId);
        $response = $fieldAttachmentsOperations->getFieldAttachments();
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
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
$moduleAPIName="leads";
$recordId="4402480774074";
$fieldsAttachmentId="4402401325010";
$destinationFolder="/PHP/php-sdk-sample/file";
GetFieldAttachments::initialize();
GetFieldAttachments::getFieldAttachments($moduleAPIName,$recordId,$fieldsAttachmentId,$destinationFolder);