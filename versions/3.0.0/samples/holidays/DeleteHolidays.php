<?php
namespace holidays;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\holidays\ActionWrapper;
use com\zoho\crm\api\holidays\APIException;
use com\zoho\crm\api\holidays\HolidaysOperations;
use com\zoho\crm\api\holidays\SuccessResponse;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DeleteHolidays
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
    public static function deleteHolidays($id)
    {
        $holidaysOperations = new HolidaysOperations("4402480813");
        $response = $holidaysOperations->deleteHoliday($id);
        if($response != null)
        {
            echo("Status Code: " . $response->getStatusCode());
            if($response->isExpected())
            {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper)
                {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getHolidays();
                    if ($actionResponses != null)
                    {
                        foreach ($actionResponses as $actionResponse)
                        {
                            if ($actionResponse instanceof SuccessResponse)
                            {
                                $successResponse = $actionResponse;
                                echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                                echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                                echo ("Details: ");
                                foreach ($successResponse->getDetails() as $key => $value)
                                {
                                    echo ($key . " : ");
                                    print_r($value);
                                    echo ("\n");
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
$id = "440248001218028";
DeleteHolidays::initialize();
DeleteHolidays::deleteHolidays($id);