<?php
namespace shifthours;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\shifthours\APIException;
use com\zoho\crm\api\shifthours\ResponseWrapper;
use com\zoho\crm\api\shifthours\Role;
use com\zoho\crm\api\shifthours\ShiftHours;
use com\zoho\crm\api\shifthours\ShiftHoursOperations;

require_once "vendor/autoload.php";

class GetShiftHour
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
    public static function getShiftHour($id)
    {
        $shiftHoursOperations = new ShiftHoursOperations("440248020813");
        $response = $shiftHoursOperations->getShiftHour($id);
        if ($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204,304)))
            {
                echo($response->getStatusCode() == 204 ? "NO Content" : "Not Modified");
                return;
            }
            if ($response->isExpected())
            {
                $responseObject = $response->getObject();
                if ($responseObject instanceof ResponseWrapper)
                {
                    $responseWrapper = $responseObject;
                    $shiftcount = $responseWrapper->getShiftCount();
                    if ($shiftcount != null)
                    {
                        echo("Shift_Count : " . "\n");
                        echo("total_shift_with_user : " . $shiftcount->getTotalShiftWithUser() . "\n");
                        echo("total_shift : " . $shiftcount->getTotalShift() . "\n");
                    }
                    $shifthours = $responseWrapper->getShiftHours();
                    if ($shifthours != null)
                    {
                        foreach ($shifthours as $shifthour)
                        {
                            if ($shifthour instanceof ShiftHours)
                            {
                                echo("name : " . $shifthour->getName() . "\n");
                                echo("same_as_everyday : " . $shifthour->getSameAsEveryday() . "\n");
                                echo("users_count : " . $shifthour->getUsersCount() . "\n");
                                echo("timezone : "); print_r($shifthour->getTimezone()); echo("\n");
                                $shiftDays = $shifthour->getShiftDays();
                                if ($shiftDays != null)
                                {
                                    echo("ShiftDays : " . "\n");
                                    foreach ($shiftDays as $shiftDay)
                                    {
                                        echo($shiftDay . "\n");
                                    }
                                }
                                $dailyTimings = $shifthour->getDailyTiming();
                                if ($dailyTimings != null)
                                {
                                    echo ("Daily_timing : " . "\n");
                                    foreach ($dailyTimings as $dailyTiming)
                                    {
                                        echo($dailyTiming . "\n");
                                    }
                                }
                                $customTiming = $shifthour->getCustomTiming();
                                if ($customTiming != null)
                                {
                                    echo("Custom Timing : " . "\n");
                                    foreach ($customTiming as $customtiming)
                                    {
                                        $shiftTiming = $customtiming->getShiftTiming();
                                        echo ("Shift Timing : " . "\n");
                                        foreach ($shiftTiming as $shifttiming)
                                        {
                                            echo($shifttiming . "\n");
                                        }
                                        echo("days : " . $customtiming->getDays() . "\n");
                                    }
                                }
                                $holidays = $shifthour->getHolidays();
                                if ($holidays != null)
                                {
                                    echo ("holidays : " . "\n");
                                    foreach ($holidays as $holiday)
                                    {
                                        echo("date : " . date_format($holiday->getDate(), 'd-m-y-H-i-s') . "\n");
                                        echo("year : " . $holiday->getYear() . "\n");
                                        echo("name : " . $holiday->getName() . "\n");
                                        echo("id : " . $holiday->getId() . "\n");
                                    }
                                }
                                $users = $shifthour->getUsers();
                                if ($users != null)
                                {
                                    echo("Users : " . "\n");
                                    foreach ($users as $user)
                                    {
                                        echo("User_Id : " . $user->getId() . "\n");
                                        echo("User_name : " . $user->getName() . "\n");
                                        echo("User_mail : " . $user->getEmail() . "\n");
                                        $role = $user->getRole();
                                        if ($role != null)
                                        {
                                            if ($role instanceof Role)
                                            {
                                                echo("Role name : " . $role->getName() . "\n");
                                                echo("Role Id : " . $role->getId() . "\n");
                                            }
                                        }
                                        echo("User_ZUID : " . $user->getZuid() . "\n");
                                        echo("effective_from : " . $user->getEffectiveFrom() . "\n");
                                    }
                                }
                                $breakHours = $shifthour->getBreakHours();
                                if ($breakHours !=null)
                                {
                                    foreach ($breakHours as $breakHour)
                                    {
                                        echo("breakHour_ID : " . $breakHour->getId() . "\n");
                                        echo("same_as_everyday : " . $breakHour->getSameAsEveryday() . "\n");
                                        $breakDays = $breakHour->getBreakdays();
                                        if ($breakDays != null)
                                        {
                                            foreach ($breakDays as $breakDay)
                                            {
                                                echo("breakDays : " . $breakDay . "\n");
                                            }
                                        }
                                        $dailyTimings = $breakHour->getDailyTiming();
                                        if ($dailyTimings != null)
                                        {
                                            foreach ($dailyTimings as $dailyTiming)
                                            {
                                                echo("dailyTiming : " . $dailyTiming . "\n");
                                            }
                                        }
                                        $breakHoursCustomTimings = $breakHour->getCustomTiming();
                                        if ($breakHoursCustomTimings != null)
                                        {
                                            foreach ($breakHoursCustomTimings as $breakHoursCustomTiming)
                                            {
                                                echo ("CustomTiming : " . "\n");
                                                $breakTimings = $breakHoursCustomTiming->getBreakTiming();
                                                if ($breakTimings != null)
                                                {
                                                    foreach ($breakTimings as $breakTiming)
                                                    {
                                                        echo("breakTiming : " . $breakTiming . "\n");
                                                    }
                                                    echo("days : " . $breakHoursCustomTiming->getDays()->getValue() . "\n");
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else if ($responseObject instanceof APIException) {
                    $exception = $responseObject;
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
$id = "347706118993007";
GetShiftHour::initialize();
GetShiftHour::getShiftHour($id);