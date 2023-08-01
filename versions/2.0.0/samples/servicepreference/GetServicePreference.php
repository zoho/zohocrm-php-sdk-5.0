<?php
namespace sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\servicepreference\APIException;
use com\zoho\crm\api\servicepreference\ResponseWrapper;
use com\zoho\crm\api\servicepreference\ServicePreferenceOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetServicePreference
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

	public static function getServicePreference()
	{
		$servicePreferenceOperations = new ServicePreferenceOperations();
		$response = $servicePreferenceOperations->getServicePreference();
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
			$responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
				$servicePreferences = $responseWrapper->getServicePreferences();
				if ($servicePreferences != null)
				{
					echo("JobSheetEnabled : " . $servicePreferences->getJobSheetEnabled());
				}
			}
			else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . ": " . $value . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
            }
		}
	}
}

GetServicePreference::initialize();
GetServicePreference::getServicePreference();