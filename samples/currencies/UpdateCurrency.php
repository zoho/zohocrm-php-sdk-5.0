<?php
namespace currencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\currencies\CurrencyFormat;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\currencies\ActionWrapper;
use com\zoho\crm\api\currencies\APIException;
use com\zoho\crm\api\currencies\SuccessResponse;
use com\zoho\crm\api\currencies\BodyWrapper;
use com\zoho\crm\api\currencies\CurrenciesOperations;

require_once "vendor/autoload.php";

class UpdateCurrency
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

    public static function updateCurrency(string $currencyId)
    {
        $currenciesOperations = new CurrenciesOperations();
        $bodyWrapper = new BodyWrapper();
        //List of Currency instances
        $currencies = array();
        $currencyClass = "com\zoho\crm\api\currencies\Currency";
        $currency = new $currencyClass();
        //true: Display ISO code before the currency value.
        //false: Display ISO code after the currency value.
        $currency->setPrefixSymbol(true);
        $currency->setExchangeRate("5.00");
        //true: The currency is active.
        //false: The currency is inactive.
        $currency->setIsActive(true);
        $format = new CurrencyFormat();
        //It can be a Period or Comma, depending on the currency.
        $format->setDecimalSeparator(new Choice("Period"));
        //It can be a Period, Comma, or Space, depending on the currency.
        $format->setThousandSeparator(new Choice("Comma"));
        $format->setDecimalPlaces(new Choice("2"));
        $currency->setFormat($format);
        array_push($currencies, $currency);
        $bodyWrapper->setCurrencies($currencies);
        //Call addCurrencies method that takes BodyWrapper instance as parameter
        $response = $currenciesOperations->updateCurrency($currencyId, $bodyWrapper);
        if ($response != null) {
            echo ("Status code" . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getCurrencies();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof SuccessResponse) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($successResponse->getDetails() as $key => $value) {
                            echo ($key . ": " . $value . "\n");
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    }
                    else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
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
            else if ($actionHandler instanceof APIException) {
                $exception = $actionHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . ": " . $value . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$currencyId="30987345654";
UpdateCurrency::initialize();
UpdateCurrency::updateCurrency($currencyId);
