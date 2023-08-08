<?php
namespace appointmentpreferences;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\appointmentpreference\APIException;
use com\zoho\crm\api\appointmentpreference\AppointmentPreferenceOperations;
use com\zoho\crm\api\appointmentpreference\GetAppointmentPreferenceParam;
use com\zoho\crm\api\appointmentpreference\ResponseWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetAppointmentPreference
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

    public static function getAppointmentPreference()
    {
        $appointmentPreferenceOperations = new AppointmentPreferenceOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetAppointmentPreferenceParam::include(), "");
        $response = $appointmentPreferenceOperations->getAppointmentPreference($paramInstance);
        if ($response != null)
        {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304)))
            {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $appointmentPreference = $responseWrapper->getAppointmentPreferences();
                if ($appointmentPreference != null)
                {
                    echo ("AppointmentPreference showJobSheet : " . $appointmentPreference->getShowJobSheet() . "\n");
                    echo ("AppointmentPreference whenDurationExceeds : " . $appointmentPreference->getWhenDurationExceeds()->getValue(). "\n");
                    echo ("AppointmentPreference allowBookingOutsideServiceAvailability : " . $appointmentPreference->getAllowBookingOutsideServiceAvailability(). "\n");
                    echo ("AppointmentPreference whenAppointmentCompleted : " . $appointmentPreference->getWhenAppointmentCompleted()->getValue(). "\n");
                    echo ("AppointmentPreference allowBookingOutsideBusinesshours : " . $appointmentPreference->getAllowBookingOutsideBusinesshours(). "\n");
                    $dealRecordConfiguration = $appointmentPreference->getDealRecordConfiguration();
                    if ($dealRecordConfiguration != null) {
                        foreach ($dealRecordConfiguration as $key => $value) {
                            if ($key == "layout") {
                                $layout = $value;
                                echo("Layout ID : " . $layout->getID(). "\n");
                                echo("LayoutName : " . $layout->getAPIName(). "\n");
                            }
                            if ($key == "field_mappings") {
                                $fieldMappings = $value;
                                echo("FieldMappings Type: " . $fieldMappings->getType(). "\n");
                                echo("FieldMappings Value: " . $fieldMappings->getValue(). "\n");
                                $field = $fieldMappings->getField();
                                if ($field != null) {
                                    echo("Field APIName :" . $field->getAPIName(). "\n");
                                    echo("Field Id : " . $field->getID(). "\n");
                                }
                            }
                        }
                    }
                }
            }
            else if ($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo ("Status : " . $exception->getStatus()->getValue() . "\n");
                echo ("Code : " . $exception->getCode()->getValue() . "\n");
                echo ("Details : " . "\n");
                foreach ($exception->getDetails() as $key => $value)
                {
                    echo ($key . " : " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
GetAppointmentPreference::initialize();
GetAppointmentPreference::getAppointmentPreference();