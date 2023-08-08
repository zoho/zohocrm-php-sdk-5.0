<?php
namespace backup;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\backup\APIException;
use com\zoho\crm\api\backup\BackupOperations;
use com\zoho\crm\api\backup\BodyWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetDetails
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
    public static function getDetails()
    {
        $backupOperations = new BackupOperations();
        $response = $backupOperations->getDetails();
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
                if ($responseHandler instanceof BodyWrapper)
                {
                    $responseWrapper = $responseHandler;
                    $backup = $responseWrapper->getBackup();
                    if ($backup != null)
                    {
                        echo("Backup Rrule: " . $backup->getRrule() . "\n");
                        echo("Backup Id: " . $backup->getId() . "\n");
                        echo("Backup StartDate: " . date_format($backup->getStartDate(), 'd-m-Y-H-i-s') . "\n");
                        echo("Backup ScheduledDate: " . date_format($backup->getScheduledDate(), 'd-m-y-H-i-s') . "\n");
                        echo("Backup Status: " . $backup->getStatus() . "\n");
                        $requester = $backup->getRequester();
                        if ($requester != null)
                        {
                            echo("Backup Requester Id: " . $requester->getId(). "\n");
                            echo("Backup Requester Name: " . $requester->getName(). "\n");
                            echo("Backup Requester Zuid:" . $requester->getZuid(). "\n");
                        }
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
GetDetails::initialize();
GetDetails::getDetails();
