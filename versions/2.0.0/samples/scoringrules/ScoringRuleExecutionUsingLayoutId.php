<?php
namespace scoringrules;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\scoringrules\APIException;
use com\zoho\crm\api\scoringrules\ScoringRulesOperations;
use com\zoho\crm\api\scoringrules\Layout;
use com\zoho\crm\api\scoringrules\SuccessResponse;
use com\zoho\crm\api\scoringrules\LayoutRequestWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class ScoringRuleExecutionUsingLayoutId
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
    public static function scoringRuleExecutionUsingLayoutId($moduleAPIName)
    {
        $scoringRulesOperations = new ScoringRulesOperations();
        $bodyWrapper = new LayoutRequestWrapper();
        $layout = new Layout();
        $layout->setId("3477061091055");
        $bodyWrapper->setLayout($layout);
        // Call scoringRuleExecutionUsingLayoutId method that takes moduleAPIName and LayoutRequestWrapper instance as parameter
        $response = $scoringRulesOperations->scoringRuleExecutionUsingLayoutId($moduleAPIName, $bodyWrapper);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if ($actionResponse instanceof SuccessResponse) {
                $successResponse = $actionResponse;
                echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo ("Details: ");
                if ($successResponse->getDetails() != null) {
                    foreach ($successResponse->getDetails() as $keyName => $keyValue) {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
            }
            else if ($actionResponse instanceof APIException) {
                $exception = $actionResponse;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                if ($exception->getDetails() != null) {
                    foreach ($exception->getDetails() as $keyName => $keyValue) {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName="leads";
ScoringRuleExecutionUsingLayoutId::initialize();
ScoringRuleExecutionUsingLayoutId::scoringRuleExecutionUsingLayoutId($moduleAPIName);
