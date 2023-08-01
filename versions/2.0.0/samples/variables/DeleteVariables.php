<?php
namespace variables;

use com\zoho\api\authenticator\OAuthBuilder;

use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\variables\APIException;
use com\zoho\crm\api\variables\ActionWrapper;
use com\zoho\crm\api\variables\SuccessResponse;
use com\zoho\crm\api\variables\VariablesOperations;
use com\zoho\crm\api\variables\DeleteVariablesParam;

require_once "vendor/autoload.php";
class DeleteVariables
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

    public static function deleteVariables(array $variableIds)
    {
        $variablesOperations = new VariablesOperations();
        $paramInstance = new ParameterMap();
        foreach ($variableIds as $variableId) {
            $paramInstance->add(DeleteVariablesParam::ids(), $variableId);
        }
        //Call deleteVariables method that takes BodyWrapper class instance as parameter
        $response = $variablesOperations->deleteVariables($paramInstance);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getVariables();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof SuccessResponse) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($successResponse->getDetails() as $key => $value) {
                            echo ($key . " : " . $value . "\n");
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
$variableIds=array("347706118948002","323202323");
DeleteVariables::initialize();
DeleteVariables::deleteVariables($variableIds);
