<?php
namespace variables;

use com\zoho\api\authenticator\OAuthBuilder;

use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\variables\APIException;
use com\zoho\crm\api\variables\ResponseWrapper;
use com\zoho\crm\api\variables\VariablesOperations;
use com\zoho\crm\api\variables\GetVariablesParam;

require_once "vendor/autoload.php";

class GetVariables
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
            ->store($store)
            ->initialize();
    }

    public static function getVariables()
    {
        $variablesOperations = new VariablesOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetVariablesParam::group(), "General");
        //Call getVariables method that takes paramInstance as parameter
        $response = $variablesOperations->getVariables($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $variables = $responseWrapper->getVariables();
                if ($variables != null) {
                    foreach ($variables as $variable) {
                        echo ("Variable APIName: " . $variable->getAPIName() . "\n");
                        echo ("Variable Name: " . $variable->getName() . "\n");
                        echo ("Variable Description: " . $variable->getDescription() . "\n");
                        echo ("Variable ID: " . $variable->getId() . "\n");
                        echo ("Variable Source: " . $variable->getSource() . "\n");
                        echo ("Variable Type: " . $variable->getType()->getValue() . "\n");
                        $variableGroup = $variable->getVariableGroup();
                        if ($variableGroup != null) {
                            echo ("Variable VariableGroup APIName: " . $variableGroup->getAPIName() . "\n");
                            echo ("Variable VariableGroup ID: " . $variableGroup->getId() . "\n");
                        }
                        echo ("Variable Value: " . $variable->getValue() . "\n");
                    }
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
GetVariables::initialize();
GetVariables::getVariables();
