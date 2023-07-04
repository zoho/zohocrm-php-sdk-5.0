<?php
namespace availablecurrencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\availablecurrencies\APIException;
use com\zoho\crm\api\availablecurrencies\AvailableCurrenciesOperations;
use com\zoho\crm\api\availablecurrencies\ResponseWrapper;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetAvailableCurrencies
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
	public static function getAvailableCurrencies()
	{
		$currenciesOperations = new AvailableCurrenciesOperations();
		$response = $currenciesOperations->getAvailableCurrencies();
		if ($response != null) {
			echo ("Status Code: " . $response->getStatusCode() . "\n");
			if (in_array($response->getStatusCode(), array(204, 304))) {
				echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
				return;
			}
			$responseHandler = $response->getObject();
			if ($responseHandler instanceof ResponseWrapper) {
				$responseWrapper = $responseHandler;
				$currenciesList = $responseWrapper->getAvailableCurrencies();
				foreach ($currenciesList as $currency) {
					echo ("Currency DisplayValue: " . $currency->getDisplayValue() . "\n");
					echo ("Currency DecimalSeparator: " . $currency->getDecimalSeparator() . "\n");
					echo ("Currency Symbol: " . $currency->getSymbol() . "\n");
                    echo ("Currency ThousandSeparator: " . $currency->getThousandSeparator() . "\n");
					echo ("Currency IsoCode: " . $currency->getIsoCode() . "\n");
					echo ("Currency DisplayName: " . $currency->getDisplayName() . "\n");
					echo ("Currency DecimalPlaces: " . $currency->getDecimalPlaces() . "\n");
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
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
			}
		}
	}
}
GetAvailableCurrencies::initialize();
GetAvailableCurrencies::getAvailableCurrencies();
