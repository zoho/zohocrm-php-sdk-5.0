<?php
namespace businesshours;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\businesshours\APIException;
use com\zoho\crm\api\businesshours\BusinessHoursOperations;
use com\zoho\crm\api\businesshours\ResponseWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetBusinessHours
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
    public static function getBusinessHours()
    {
        $businessHoursOperations = new BusinessHoursOperations("440813");
        $response = $businessHoursOperations->getBusinessHours();
        if ($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->getStatusCode() == 204 || $response->getStatusCode() == 304)
            {
                echo($response->getStatusCode() == 204 ? "No content" : "Not Modified");
                return;
            }
            if($response->isExpected())
            {
                $responseObject = $response->getObject();
                if ($responseObject instanceof ResponseWrapper)
                {
                    $responseWrapper = $responseObject;
                    $businessHours = $responseWrapper->getBusinessHours();
                    $businessDays = $businessHours->getBusinessDays();
                    if ($businessDays != null)
                    {
                        echo("BusinessDays: " . "\n");
                        foreach ($businessDays as $businessDay)
                        {
                            echo($businessDay->getValue() . "\n");
                        }
                    }
                    else
                    {
                        echo("BusinessDays : null" . "\n");
                    }
                    $customTiming = $businessHours->getCustomTiming();
                    if($customTiming != null)
                    {
                        echo("Custom_Timing: " . "\n");
                        foreach ($customTiming as $bhct)
                        {
                            echo("days: " . $bhct->getDays()->getValue() . "\n");
                            $businessTimings = $bhct->getBusinessTiming();
                            foreach($businessTimings as $businessTiming)
                            {
                                echo("BusinessTimings: " . $businessTiming . "\n");
                            }
                        }
                    }
                    else
                    {
                        echo("Custom_Timing : null" . "\n");
                    }
                    $dailyTimings = $businessHours->getDailyTiming();
                    if ($dailyTimings != null)
                    {
                        echo("daily_timings: " . "\n");
                        foreach ($dailyTimings as $dailyTiming)
                        {
                            echo($dailyTiming . "\n");
                        }
                    }
                    else
                    {
                        echo("daily_timings : null" . "\n");
                    }
                    echo("Week_starts_on: " . $businessHours->getWeekStartsOn()->getValue() . "\n");
                    echo("same_as_every_day : " . $businessHours->getSameAsEveryday() . "\n");
                    echo("businessHours_Id: " . $businessHours->getId() . "\n");
                    echo("businessHours_type: " . $businessHours->getType()->getValue() . "\n");
                }
                elseif ($responseObject instanceof APIEXception)
                {
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
GetBusinessHours::initialize();
GetBusinessHours::getBusinessHours();