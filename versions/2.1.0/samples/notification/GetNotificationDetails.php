<?php
namespace notification;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\notifications\GetNotificationsParam;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\notifications\APIException;
use com\zoho\crm\api\notifications\NotificationsOperations;
use com\zoho\crm\api\notifications\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetNotificationDetails
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

    public static function getNotificationDetails()
    {
        $notificationOperations = new NotificationsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetNotificationsParam::channelId(), "1006800211");
        $paramInstance->add(GetNotificationsParam::module(), "Accounts");
        $paramInstance->add(GetNotificationsParam::page(), 1);
        $paramInstance->add(GetNotificationsParam::perPage(), 2);
        $response = $notificationOperations->getNotifications($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrapper = $responseHandler;
                    $notifications = $responseWrapper->getWatch();
                    foreach ($notifications as $notification) {
                        echo ("Notification NotifyOnRelatedAction: " . $notification->getNotifyOnRelatedAction() . "\n");
                        echo ("Notification ChannelExpiry: ");
                        print_r($notification->getChannelExpiry());
                        echo ("Notification ResourceUri: " . $notification->getResourceUri() . "\n");
                        echo ("Notification ResourceId: " . $notification->getResourceId() . "\n");
                        echo ("Notification NotifyUrl: " . $notification->getNotifyUrl() . "\n");
                        echo ("Notification ResourceName: " . $notification->getResourceName() . "\n");
                        echo ("Notification ChannelId: " . $notification->getChannelId() . "\n");
                        $fields = $notification->getEvents();
                        if ($fields != null) {
                            foreach ($fields as $fieldName) {
                                echo ("Notification Events: " . $fieldName . "\n");
                            }
                        }
                        echo ("Notification Token: " . $notification->getToken() . "\n");
                    }
                    $info = $responseWrapper->getInfo();
                    if ($info != null) {
                        echo ("Record Info PerPage: " . $info->getPerPage() . "\n");
                        echo ("Record Info Count: " . $info->getCount() . "\n");
                        echo ("Record Info Page: " . $info->getPage() . "\n");
                        echo ("Record Info MoreRecords: ");
                        print_r($info->getMoreRecords());
                        echo ("\n");
                    }
                }
                else if ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
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
            } else if ($response->getStatusCode() != 204) { //If response is not as expected
                print_r($response);
            }
        }
    }
}
GetNotificationDetails::initialize();
GetNotificationDetails::getNotificationDetails();
