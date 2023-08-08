<?php
namespace resheduledhistory;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\reschedulehistory\ActionWrapper;
use com\zoho\crm\api\reschedulehistory\APIException;
use com\zoho\crm\api\reschedulehistory\AppointmentName;
use com\zoho\crm\api\reschedulehistory\BodyWrapper;
use com\zoho\crm\api\reschedulehistory\RescheduleHistory;
use com\zoho\crm\api\reschedulehistory\RescheduleHistoryOperations;
use com\zoho\crm\api\reschedulehistory\SuccessResponse;
use com\zoho\crm\api\reschedulehistory\User;
use com\zoho\crm\api\util\Choice;

include_once "vendor/autoload.php";

class AddAppointmentsRescheduledHistory
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
    public static function addAppointmentsRescheduledHistory()
    {
        $rescheduleHistoryOperations = new RescheduleHistoryOperations();
        $request = new BodyWrapper();
        $data = [];
        $rescheduledHistory = new RescheduleHistory();
        $appointmentName = new AppointmentName();
        $appointmentName->setName("new");
        $appointmentName->setId("440248001334014");
        $rescheduledHistory->setAppointmentName($appointmentName);
        $rescheduledBy = new User();
        $rescheduledBy->setId("440248254001");
        $rescheduledBy->setName("username");
        $rescheduledHistory->setRescheduledBy($rescheduledBy);
        $rescheduledHistory->setRescheduledFrom(date_create("2023-07-01T01:00:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
        $rescheduledHistory->setRescheduledTo(date_create("2023-07-10T9:30:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
        $rescheduledHistory->setRescheduledTime(date_create("2023-07-20T11:00:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
        $rescheduledHistory->setRescheduleReason("By Customer");
        array_push($data, $rescheduledHistory);
        $request->setData($data);
        $response = $rescheduleHistoryOperations->addAppointmentsRescheduledHistory($request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                            echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : ");
                                print_r($value);
                                echo ("\n");
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                            echo ("Code: " . $exception->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            foreach ($exception->getDetails() as $key => $value) {
                                echo ($key . " : " . $value . "\n");
                            }
                            echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                        }
                    }
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            } else {
                print_r($response);
            }
        }
    }
}
AddAppointmentsRescheduledHistory::initialize();
AddAppointmentsRescheduledHistory::addAppointmentsRescheduledHistory();