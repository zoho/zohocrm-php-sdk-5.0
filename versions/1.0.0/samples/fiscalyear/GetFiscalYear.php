<?php
namespace fiscalyear;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\fiscalyear\APIException;
use com\zoho\crm\api\fiscalyear\FiscalYearOperations;
use com\zoho\crm\api\fiscalyear\ResponseWrapper;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetFiscalYear
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
    public static function getFiscalYear()
    {
        $fiscalYearOperations = new FiscalYearOperations();
        $response = $fiscalYearOperations->getFiscalYear();
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $fiscalYear = $responseWrapper->getFiscalYear();
                if ($fiscalYear != null)
                {
                    echo("FiscalYear startMonth : " . $fiscalYear->getStartMonth()->getValue() . "\n");
                    echo("FiscalYear DisplayBasedOn : " . $fiscalYear->getDisplayBasedOn()->getValue() . "\n");
                    echo("FiscalYear ID : " . $fiscalYear->getId() . "\n");
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
GetFiscalYear::initialize();
GetFiscalYear::getFiscalYear();