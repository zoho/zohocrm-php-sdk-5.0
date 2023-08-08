<?php
namespace cancelmeetings;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\cancelmeetings\ActionWrapper;
use com\zoho\crm\api\cancelmeetings\APIException;
use com\zoho\crm\api\cancelmeetings\BodyWrapper;
use com\zoho\crm\api\cancelmeetings\CancelMeetingsOperations;
use com\zoho\crm\api\cancelmeetings\Notify;
use com\zoho\crm\api\cancelmeetings\SuccessResponse;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class CancelMeeting
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
    public static function cancelMeeting($eventId)
    {
        $cancelMeetingsOperations = new CancelMeetingsOperations($eventId);
        $request = new BodyWrapper();
        $data = array();
        $notify = new Notify();
        $notify->setSendCancellingMail(false);
        array_push($data, $notify);
        $request->setData($data);
        $response = $cancelMeetingsOperations->cancelMeetings($request);
        if($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if($response->isExpected())
            {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper)
                {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
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
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo("Code: " . $exception->getCode()->getValue() . "\n");
                            echo("Details: ");
                            foreach ($exception->getDetails() as $key => $value) {
                                echo($key . " : " . $value . "\n");
                            }
                            echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                        }
                    }
                }
                elseif ($actionHandler instanceof APIException)
                {
                    $exception = $actionHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    echo("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
$eventId = 440248001327058;
CancelMeeting::initialize();
CancelMeeting::cancelMeeting($eventId);