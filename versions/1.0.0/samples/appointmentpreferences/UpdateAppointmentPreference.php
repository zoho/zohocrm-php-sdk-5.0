<?php
namespace appointmentpreferences;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\appointmentpreference\ActionWrapper;
use com\zoho\crm\api\appointmentpreference\APIException;
use com\zoho\crm\api\appointmentpreference\AppointmentPreference;
use com\zoho\crm\api\appointmentpreference\AppointmentPreferenceOperations;
use com\zoho\crm\api\appointmentpreference\BodyWrapper;
use com\zoho\crm\api\appointmentpreference\Field;
use com\zoho\crm\api\appointmentpreference\FieldMappings;
use com\zoho\crm\api\appointmentpreference\Layout;
use com\zoho\crm\api\appointmentpreference\SuccessResponse;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateAppointmentPreference
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
    public static function updateAppointmentPreference()
    {
        $appointmentPreferenceOperations = new AppointmentPreferenceOperations();
        $request = new BodyWrapper();
        $appointmentPreferences = new AppointmentPreference();
        $appointmentPreferences->setAllowBookingOutsideBusinesshours(false);
        $appointmentPreferences->setAllowBookingOutsideServiceAvailability(true);
        $appointmentPreferences->setWhenAppointmentCompleted(new Choice("create_deal"));
        $appointmentPreferences->setWhenDurationExceeds(new Choice("ask_appointment_provider_to_complete"));
        $appointmentPreferences->setShowJobSheet(true);
        $dealRecordConfiguration = [];
        $layout = new Layout();
        $layout->setAPIName("Standard");
        $layout->setId("440248173");
        $mappings = [];
        $fieldMappings = new FieldMappings();
        $fieldMappings->setType(new Choice("static"));
        $fieldMappings->setValue("Closed Won");
        $field = new Field();
        $field->setId("440248001182075");
        $field->setAPIName("Stage");
        $fieldMappings->setField($field);
        array_push($mappings, $fieldMappings);
        $dealRecordConfiguration = ["layout" => $layout,"field_mappings" => $mappings];
        $appointmentPreferences->setDealRecordConfiguration($dealRecordConfiguration);
        $request->setAppointmentPreferences($appointmentPreferences);
        $response = $appointmentPreferenceOperations->updateAppointmentPreference($request);
        if ($response != null) {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponse = $actionWrapper->getAppointmentPreferences();
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
                        echo("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    } else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
                        echo("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo("Code: " . $exception->getCode()->getValue() . "\n");
                        echo("Details: ");
                        foreach ($exception->getDetails() as $key => $value) {
                            echo($key . " : " . $value . "\n");
                        }
                        echo("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                    }
                } else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    echo("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo($key . " : " . $value . "\n");
                    }
                    echo("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            } else {
                print_r($response);
            }
        }
    }
}
UpdateAppointmentPreference::initialize();
UpdateAppointmentPreference::updateAppointmentPreference();