<?php
namespace variablegroups;

use com\zoho\api\authenticator\OAuthBuilder;

use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\variablegroups\APIException;
use com\zoho\crm\api\variablegroups\ResponseWrapper;
use com\zoho\crm\api\variablegroups\VariableGroupsOperations;

require_once "vendor/autoload.php";

class GetVariableGroupById
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

    public static function getVariableGroupById(string $variableGroupId)
    {
        $variableGroupsOperations = new VariableGroupsOperations();
        //Call getVariableGroupById method that takes variableGroupId as parameter
        $response = $variableGroupsOperations->getVariableGroupById($variableGroupId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $variableGroups = $responseWrapper->getVariableGroups();
                if ($variableGroups != null) {
                    foreach ($variableGroups as $variableGroup) {
                        echo ("VariableGroup DisplayLabel: " . $variableGroup->getDisplayLabel() . "\n");
                        echo ("VariableGroup APIName: " . $variableGroup->getAPIName() . "\n");
                        echo ("VariableGroup Name: " . $variableGroup->getName() . "\n");
                        echo ("VariableGroup Description: " . $variableGroup->getDescription() . "\n");
                        echo ("VariableGroup ID: " . $variableGroup->getId() . "\n");
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
$variableGroupId="34770613089001";
GetVariableGroupById::initialize();
GetVariableGroupById::getVariableGroupById($variableGroupId);
