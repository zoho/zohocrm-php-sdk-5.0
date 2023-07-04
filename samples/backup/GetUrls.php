<?php
namespace backup;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\backup\APIException;
use com\zoho\crm\api\backup\BackupOperations;
use com\zoho\crm\api\backup\UrlsWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetUrls
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
    public static function getUrls()
    {
        $backupOperations = new BackupOperations();
        $response = $backupOperations->getUrls();
        if ($response != null)
        {
            echo ("Status Code : " . $response->getStatusCode() . "\n");
            if ($response->getStatusCode() == 204 || $response->getStatusCode() == 304)
            {
                echo ($response->getStatusCode() == 204 ? "No Content" : "Not Modified");
                return;
            }
            if ($response->isExpected())
            {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof UrlsWrapper)
                {
                    $responseWrapper = $responseHandler;
                    $urls = $responseWrapper->getUrls();
                    if ($urls != null)
                    {
                        $dataLinks = $urls->getDataLinks();
                        if ($dataLinks != null)
                        {
                            echo("Urls DataLinks: " . "\n");
                            foreach ($dataLinks as $dataLink)
                            {
                                echo($dataLink . "\n");
                            }
                        }
                        $attachmentLinks = $urls->getAttachmentLinks();
                        if ($attachmentLinks != null)
                        {
                            echo("Urls Attachments: ");
                            foreach ($attachmentLinks as $attachmentLink)
                            {
                                echo($attachmentLink);
                            }
                        }
                        echo ("Urls ExpiryDate: " . date_format($urls->getExpiryDate(), 'd-m-y-H-i-s'));
                    }
                }
                elseif ($responseHandler instanceof APIException)
                {
                    $exception = $responseHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    if ($exception->getDetails() != null)
                    {
                        echo ("Details: \n");
                        foreach ($exception->getDetails() as $keyName => $keyValue)
                        {
                            echo ($keyName . ": " . $keyValue . "\n");
                        }
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
GetUrls::initialize();
GetUrls::getUrls();
