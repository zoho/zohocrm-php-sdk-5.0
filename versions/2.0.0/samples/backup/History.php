<?php
namespace backup;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\backup\APIException;
use com\zoho\crm\api\backup\BackupOperations;
use com\zoho\crm\api\backup\HistoryWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class History
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
    public static function history()
    {
        $backupOperations = new BackupOperations();
        $paramInstance = new ParameterMap();
        $response = $backupOperations->history($paramInstance);
        if ($response != null) {
            echo("Status Code : " . $response->getStatusCode());
            if ($response->getStatusCode() == 204 || $response->getStatusCode() == 304) {
                echo($response->getStatusCode() == 204 ? "No Content" : "Not Modified");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof HistoryWrapper) {
                    $historyWrapper = $responseHandler;
                    $history = $historyWrapper->getHistory();
                    foreach ($history as $history1) {
                        if ($history1 instanceof \com\zoho\crm\api\backup\History) {
                            echo("History Id: " . $history1->getId());
                            $doneBy = $history1->getDoneBy();
                            if ($doneBy != null) {
                                echo("History DoneBy Id: " . $doneBy->getId());
                                echo("History DoneBy Name: " . $doneBy->getName());
                                echo("History DoneBy Zuid: " . $doneBy->getZuid());
                            }
                            echo("History LogTime: " . date_format($history1->getLogTime(), 'd-m-y-H-i-s') . "\n");
                            echo("History State: " . $history1->getState());
                            echo("History Action: " . $history1->getAction());
                            echo("History RepeatType: " . $history1->getRepeatType());
                            echo("History FileName: " . $history1->getFileName());
                            echo("History Count: " . $history1->getCount());
                        }
                        $info = $historyWrapper->getInfo();
                        if ($info != null) {
                            if ($info->getPerPage() != null) {
                                echo("History Info PerPage: " . strval($info->getPerPage()));
                            }
                            if ($info->getCount() != null) {
                                echo("History Info Count: " . strval($info->getCount()));
                            }
                            if ($info->getPage() != null) {
                                echo("History Info Page: " . strval($info->getPage()));
                            }
                            if ($info->getMoreRecords() != null) {
                                echo("History Info MoreRecords: " . strval($info->getMoreRecords()));
                            }
                        }
                    }
                } elseif ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    if ($exception->getDetails() != null) {
                        echo("Details: \n");
                        foreach ($exception->getDetails() as $keyName => $keyValue) {
                            echo($keyName . ": " . $keyValue . "\n");
                        }
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
History::initialize();
History::history();
