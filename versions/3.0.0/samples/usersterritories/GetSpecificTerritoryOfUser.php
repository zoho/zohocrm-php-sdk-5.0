<?php
namespace usersterritories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\usersterritories\APIException;
use com\zoho\crm\api\usersterritories\ResponseWrapper;
use com\zoho\crm\api\usersterritories\UsersTerritoriesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetSpecificTerritoryOfUser
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

    public static function getSpecificTerritoryOfUser($userId, $territoryId)
    {
        $usersTerritoriesOperations = new UsersTerritoriesOperations($userId);
        // Call getTerritoriesOfUser method that takes territoryId, userId and ParameterMap instance as parameters
        $response = $usersTerritoriesOperations->getTerritoryOfUser($territoryId, $userId);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $usersTerritory = $responseWrapper->getTerritories();
                foreach ($usersTerritory as $territory) {
                    echo ("User Territory ID: " . $territory->getId() . "\n");
                    $manager = $territory->getManager();
                    if ($manager != null) {
                        echo ("User Territory Manager Name: " . $manager->getName() . "\n");
                        echo ("User Territory Manager ID: " . $manager->getId() . "\n");
                    }
                    $reportingTo = $territory->getReportingTo();
                    if ($reportingTo != null) {
                        echo ("User Territory ReportingTo Name: " . $reportingTo->getName() . "\n");
                        echo ("User Territory ReportingTo ID: " . $reportingTo->getId() . "\n");
                    }
                    echo ("User Territory Name: " . $territory->getName() . "\n");
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
$userId = "34770615791024";
$territoryId = "34770613051397";
GetSpecificTerritoryOfUser::initialize();
GetSpecificTerritoryOfUser::getSpecificTerritoryOfUser($userId,$territoryId);