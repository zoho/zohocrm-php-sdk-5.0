<?php
namespace usersterritories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersterritories\APIException;
use com\zoho\crm\api\usersterritories\BulkValidation;
use com\zoho\crm\api\usersterritories\UsersTerritoriesOperations;
use com\zoho\crm\api\usersterritories\Validation;
use com\zoho\crm\api\usersterritories\ValidationWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class ValidateBeforeTransferForAllTerritories
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
    public static function validateBeforeTransferForAllTerritories($userId)
    {
        $usersTerritoriesOperations = new UsersTerritoriesOperations();
        $response = $usersTerritoriesOperations->validateBeforeTransferForAllTerritories($userId);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ValidationWrapper) {
                $responseWrapper = $responseHandler;
                $usersTerritory = $responseWrapper->getValidateBeforeTransfer();
                foreach ($usersTerritory as $validation) {
                    if ($validation instanceof BulkValidation) {
                        echo ("User Territory Validation Alert : " . $validation->getAlert() . "\n");
                        echo ("User Territory Validation Assignment : " . $validation->getAssignment() . "\n");
                        echo ("User Territory Validation Criteria : " . $validation->getCriteria() . "\n");
                        echo ("User Territory Validation Name : " . $validation->getName() . "\n");
                        echo ("User Territory Validation Id : " . $validation->getId() . "\n");
                    } else if ($validation instanceof Validation) {
                        echo ("User Territory ID: " . $validation->getId() . "\n");
                        echo ("User Territory Name: " . $validation->getName() . "\n");
                        echo ("User Territory Records: " . $validation->getRecords() . "\n");
                    }
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
$userId="34770615791024";
ValidateBeforeTransferForAllTerritories::initialize();
ValidateBeforeTransferForAllTerritories::validateBeforeTransferForAllTerritories($userId);
