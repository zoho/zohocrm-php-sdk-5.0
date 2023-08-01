<?php
namespace shifthours;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\shifthours\ActionWrapper;
use com\zoho\crm\api\shifthours\APIException;
use com\zoho\crm\api\shifthours\BreakHours;
use com\zoho\crm\api\shifthours\BreakHoursCustomTiming;
use com\zoho\crm\api\shifthours\CustomTiming;
use com\zoho\crm\api\shifthours\Holidays;
use com\zoho\crm\api\shifthours\BodyWrapper;
use com\zoho\crm\api\shifthours\ShiftHours;
use com\zoho\crm\api\shifthours\ShiftHoursOperations;
use com\zoho\crm\api\shifthours\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateShiftHours
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
    public static function updateShiftHours()
    {
        $shifthoursOperations = new ShiftHoursOperations("44024820813");
        $request = new BodyWrapper();
        $shiftHours = array();
        $shifthour = new ShiftHours();
        $shifthour->setId("4402481216036");
        $shifthour->setTimezone(new DateTimeZone("Asia/Kolkata"));
        $shifthour->setName("shift hour updated");
        $shifthour->setSameAsEveryday(true);
        $dailyTimings = array();
        array_push($dailyTimings, "10:00");
        array_push($dailyTimings, "19:00");
        $shifthour->setDailyTiming($dailyTimings);
        // when same_as_everyday is false
//        $customTimings = array();
//        $customTiming = new CustomTiming();
//        $shiftTiming = array();
//        array_push($shiftTiming, "10:00");
//        array_push($shiftTiming, "19:00");
//        $customTiming->setShiftTiming($shiftTiming);
//        $customTiming->setDays(new Choice("Monday"));
//        array_push($customTimings, $customTiming);
//        $customTiming1 = new CustomTiming();
//        $shiftTiming1 = array();
//        array_push($shiftTiming1, "10:00");
//        array_push($shiftTiming1, "18:00");
//        $customTiming1->setShiftTiming($shiftTiming1);
//        $customTiming1->setDays(new Choice("Tuesday"));
//        array_push($customTimings, $customTiming1);
//        $shifthour->setCustomTiming($customTimings);
//        $users = array();
//        $user = new Users();
//        $user->setId("30212312");
//        $user->setEffectiveFrom(date_format(new \DateTime("2023:12:12"), "y:m:d"));
//        array_push($users, $user);
//        $shifthour->setUsers($users);
        $holidays = array();
        $holiday = new Holidays();
        $holiday->setDate(new \DateTime("2023-06-12"));
        $holiday->setId("440248001216038");
        $holiday->setName("Holi day");
        $holiday->setYear(2023);
        array_push($holidays, $holiday);
        $shifthour->setHolidays($holidays);
//        $breakHours = array();
//        $breakHour = new BreakHours();
//        $breakHour->setId("303244020553");
//        $breakDays = array();
//        array_push($breakDays, new Choice("Monday"));
//        $breakHour->setBreakDays($breakDays);
//        $breakHour->setSameAsEveryday(true);
//        // when same_as_everyday is true
//        $dailyTimingForBreakHours = array();
//        array_push($dailyTimingForBreakHours, "12:00");
//        array_push($dailyTimingForBreakHours, "12:15");
//        $breakHour->setDailyTiming($dailyTimingForBreakHours);
//        array_push($breakHours, $breakHour);
//        $shifthour->setBreakHours($breakHours);
//        // when same_as_everyday is false
//        $customtimingsforbreakhours = array();
//        $customTimingforBreakHour = new BreakHoursCustomTiming();
//        $breakTimings = array();
//        array_push($breakTimings, "12:00");
//        array_push($breakTimings, "12:15");
//        $customTimingforBreakHour->setBreakTiming($breakTimings);
//        $customTimingforBreakHour->setDays(new Choice("Monday"));
//        array_push($customtimingsforbreakhours, $customTimingforBreakHour);
//        $breakHour->setCustomTiming($customtimingsforbreakhours);
//        array_push($breakHours, $breakHour);
//        $shifthour->setBreakHours($breakHours);
        //
        array_push($shiftHours, $shifthour);
        $request->setShiftHours($shiftHours);
        $response = $shifthoursOperations->updateShiftHours($request);
        if($response != null) {
            echo("Status Code: " . $response->getStatusCode());
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getShiftHours();
                    if ($actionResponses != null)
                    {
                        foreach ($actionResponses as $actionResponse)
                        {
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
                            } else if ($actionResponse instanceof APIException) {
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
                } elseif ($actionHandler instanceof APIException) {
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
UpdateShiftHours::initialize();
UpdateShiftHours::updateShiftHours();