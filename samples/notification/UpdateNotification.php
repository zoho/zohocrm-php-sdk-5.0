<?php
namespace notification;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\notifications\APIException;
use com\zoho\crm\api\notifications\ActionWrapper;
use com\zoho\crm\api\notifications\BodyWrapper;
use com\zoho\crm\api\notifications\NotificationsOperations;
use com\zoho\crm\api\notifications\SuccessResponse;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\notifications\Notification;

require_once "vendor/autoload.php";

class UpdateNotification
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

    public static function updateNotification()
    {
        $notificationOperations = new NotificationsOperations();
        $bodyWrapper = new BodyWrapper();
        $notificationList = array();
        $notification = new Notification();
        $notification->setChannelId("1006800211");
        $events = array();
        array_push($events, "Deals.all");
        //To subscribe based on particular operations on given modules.
        $notification->setEvents($events);
        $notification->setChannelExpiry(date_create("2021-02-26T15:28:34+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
        //To ensure that the notification is sent from Zoho CRM, by sending back the given value in notification URL body.
        //By using this value, user can validate the notifications.
        $notification->setToken("TOKEN_FOR_VERIFICATION_OF_10068002");
        //URL to be notified (POST request)
        $notification->setNotifyUrl("https://www.zohoapis.com");
        //Add Notification instance to the list
        array_push($notificationList, $notification);
        $bodyWrapper->setWatch($notificationList);
        //Call updateNotification method that takes BodyWrapper instance as parameters
        $response = $notificationOperations->updateNotification($bodyWrapper);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getWatch();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                            echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                            echo ("Details: \n");
                            foreach ($successResponse->getDetails() as $keyName => $value) {
                                if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                    if ($value[0] instanceof Notification) {
                                        $eventList = $value;
                                        foreach ($eventList as $event) {
                                            echo ("Notification ChannelExpiry: ");
                                            print_r($event->getChannelExpiry());
                                            echo ("Notification ResourceUri: " . $event->getResourceUri() . "\n");
                                            echo ("Notification ResourceId: " . $event->getResourceId() . "\n");
                                            echo ("Notification ResourceName: " . $event->getResourceName() . "\n");
                                            echo ("Notification ChannelId: " . $event->getChannelId() . "\n");
                                        }
                                    }
                                } else {
                                    echo ($keyName . ": " . $value);
                                }
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo ("Code: " . $exception->getCode()->getValue() . "\n");
                            if ($exception->getDetails() != null) {
                                echo ("Details: \n");
                                foreach ($exception->getDetails() as $keyName => $keyValue) {
                                    echo ($keyName . ": " . $keyValue . "\n");
                                }
                            }
                            echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                        }
                    }
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo ("Status: " . $exception->getStatus()->getValue());
                    echo ("Code: " . $exception->getCode()->getValue());
                    echo ("Details: ");
                    if ($exception->getDetails() != null) {
                        echo ("Details: \n");
                        foreach ($exception->getDetails() as $keyName => $keyValue) {
                            echo ($keyName . ": " . $keyValue . "\n");
                        }
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            } else { //If response is not as expected
                print_r($response);
            }
        }
    }
}
UpdateNotification::initialize();
UpdateNotification::updateNotification();
