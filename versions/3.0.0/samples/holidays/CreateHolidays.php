<?php
namespace holidays;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\holidays\ActionWrapper;
use com\zoho\crm\api\holidays\APIException;
use com\zoho\crm\api\holidays\BusinessHoliday;
use com\zoho\crm\api\holidays\CreateBusinessHoliday;
use com\zoho\crm\api\holidays\CreateShiftHoliday;
use com\zoho\crm\api\holidays\HolidaysOperations;
use com\zoho\crm\api\holidays\ShiftHoliday;
use com\zoho\crm\api\holidays\ShiftHour;
use com\zoho\crm\api\holidays\SuccessResponse;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class CreateHolidays
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
    public static function createHolidays()
    {
        $holidaysOperations = new HolidaysOperations("44024820813");

        // when type is business_holiday
        $request = new CreateBusinessHoliday();
		$holidays = [];
		$holiday = new BusinessHoliday();
		$holiday->setName("Holiday 2");
		$holiday->setDate(new \DateTime('2023-12-1'));
		$holiday->setType(new Choice("business_holiday"));
		array_push($holidays, $holiday);
		$request->setHolidays($holidays);

        //when type is Shift_holiday
        $request1 = new CreateShiftHoliday();
		$shiftholidays = [];
		$shiftholiday = new ShiftHoliday();
		$shifthour = new ShiftHour();
		$shifthour->setName("shift hour for TX");
		$shifthour->setId("4402481331047");
		$shiftholiday->setShiftHour($shifthour);
		$shiftholiday->setName("shiftholiday");
		$shiftholiday->setType(new Choice("shift_holiday"));
		$shiftholiday->setDate(new \DateTime('2023-8-22'));
		array_push($shiftholidays, $shiftholiday);
		$request1->setHolidays($shiftholidays);

        $response = $holidaysOperations->createHolidays($request1);
        if($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
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
CreateHolidays::initialize();
CreateHolidays::createHolidays();