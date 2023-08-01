<?php
namespace businesshours;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\businesshours\ActionWrapper;
use com\zoho\crm\api\businesshours\APIException;
use com\zoho\crm\api\businesshours\BodyWrapper;
use com\zoho\crm\api\businesshours\BreakHoursCustomTiming;
use com\zoho\crm\api\businesshours\BusinessHours;
use com\zoho\crm\api\businesshours\BusinessHoursCreated;
use com\zoho\crm\api\businesshours\BusinessHoursOperations;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class CreateBusinessHours
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
    public static function createBusinessHours()
    {
        $businessHoursOperations = new BusinessHoursOperations("44024020813");
        $request = new BodyWrapper();
        $businessHours = new BusinessHours();
        $businessDays = array();
        array_push($businessDays, new Choice("Monday"));
        $businessHours->setBusinessDays($businessDays);
        $businessHours->setWeekStartsOn(new Choice("Monday"));
        $bhct = new BreakHoursCustomTiming();
        $bhct->setDays(new Choice("Monday"));
        $businessTiming =array();
        array_push($businessTiming, "10:00");
        array_push($businessTiming, "11:00");
        $bhct->setBusinessTiming($businessTiming);
        $customTiming = array();
        array_push($customTiming, $bhct);
        $businessHours->setCustomTiming($customTiming);
        $businessHours->setSameAsEveryday(false);
        // when sameAsEveryDay is true
//        $dailyTiming = array();
//        array_push($dailyTiming, "10:00");
//        array_push($dailyTiming, "11:00");
        //
        $businessHours->setType(new Choice("custom"));
        $request->setBusinessHours($businessHours);
        $response = $businessHoursOperations->createBusinessHours($request);
        if ($response != null)
        {
            echo ("Status Code: " . $response->getStatusCode());
            if($response->isExpected())
            {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper)
                {
                    $actionWrapper = $actionHandler;
                    $actionResponse = $actionWrapper->getBusinessHours();
                    if ($actionResponse instanceof BusinessHoursCreated)
                    {
                        $businessHoursCreated = $actionResponse;
                        echo ("Status: " . $businessHoursCreated->getStatus()->getValue() . "\n");
                        echo ("Code: " . $businessHoursCreated->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($businessHoursCreated->getDetails() as $key => $value)
                        {
                            echo ($key . " : ");
                            print_r($value);
                            echo ("\n");
                        }
                        echo ("Message: " . ($businessHoursCreated->getMessage() instanceof Choice ? $businessHoursCreated->getMessage()->getValue() : $businessHoursCreated->getMessage()) . "\n");
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
CreateBusinessHours::initialize();
CreateBusinessHours::createBusinessHours();