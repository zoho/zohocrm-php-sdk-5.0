<?php
namespace currencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\currencies\APIException;
use com\zoho\crm\api\currencies\BodyWrapper;
use com\zoho\crm\api\currencies\CurrenciesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetCurrency
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

    public static function getCurrency(string $currencyId)
    {
        $currenciesOperations = new CurrenciesOperations();
        //Call getCurrency method
        $response = $currenciesOperations->getCurrency($currencyId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $currenciesList = $responseWrapper->getCurrencies();
                foreach ($currenciesList as $currency) {
                    echo ("Currency Symbol: " . $currency->getSymbol() . "\n");
                    echo ("Currency CreatedTime: ");
                    print_r($currency->getCreatedTime());
                    echo ("\n");
                    echo ("Currency IsActive: " . $currency->getIsActive() . "\n");
                    echo ("Currency ExchangeRate: " . $currency->getExchangeRate() . "\n");
                    $format = $currency->getFormat();
                    if ($format != null) {
                        echo ("Currency Format DecimalSeparator: " . $format->getDecimalSeparator()->getValue() . "\n");
                        echo ("Currency Format ThousandSeparator: " . $format->getThousandSeparator()->getValue() . "\n");
                        echo ("Currency Format DecimalPlaces: " . $format->getDecimalPlaces()->getValue() . "\n");
                    }
                    $createdBy = $currency->getCreatedBy();
                    if ($createdBy != null) {
                        echo ("Currency CreatedBy User-Name: " . $createdBy->getName() . "\n");
                        echo ("Currency CreatedBy User-ID: " . $createdBy->getId() . "\n");
                    }
                    echo ("Currency PrefixSymbol: " . $currency->getPrefixSymbol() . "\n");
                    echo ("Currency IsBase: " . $currency->getIsBase() . "\n");
                    echo ("Currency ModifiedTime: ");
                    print_r($currency->getModifiedTime());
                    echo ("\n");
                    echo ("Currency Name: " . $currency->getName() . "\n");
                    $modifiedBy = $currency->getModifiedBy();
                    if ($modifiedBy != null) {
                        echo ("Currency ModifiedBy User-Name: " . $modifiedBy->getName() . "\n");
                        echo ("Currency ModifiedBy User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo ("Currency Id: " . $currency->getId() . "\n");
                    echo ("Currency IsoCode: " . $currency->getIsoCode() . "\n");
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
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$currencyId="3477061006008002";
GetCurrency::initialize();
GetCurrency::getCurrency($currencyId);
