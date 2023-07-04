<?php
namespace role;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\roles\APIException;
use com\zoho\crm\api\roles\RolesOperations;
use com\zoho\crm\api\roles\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetRole
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
    /**
     * <h3> Get Role </h3>
     * This method is used to retrieve the data of single role through an API request and print the response.
     * @param roleId The ID of the Role to be obtained
     * @throws Exception
     */
    public static function getRole(string $roleId)
    {
        //example, roleId = "34781";
        $rolesOperations = new RolesOperations();
        //Call getRoles method
        $response = $rolesOperations->getRole($roleId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $roles = $responseWrapper->getRoles();
                foreach ($roles as $role) {
                    echo ("Role DisplayLabel: " . $role->getDisplayLabel() . "\n");
                    $forecastManager = $role->getForecastManager();
                    if ($forecastManager != null) {
                        echo ("Role Forecast Manager User-ID: " . $forecastManager->getId() . "\n");
                        echo ("Role Forecast Manager User-Name: " . $forecastManager->getName() . "\n");
                    }
                    echo ("Role ShareWithPeers: ");
                    print_r($role->getShareWithPeers());
                    echo ("\n");
                    echo ("Role Name: " . $role->getName() . "\n");
                    echo ("Role Description: " . $role->getDescription() . "\n");
                    echo ("Role ID: " . $role->getId() . "\n");
                    $reportingTo = $role->getReportingTo();
                    if ($reportingTo != null) {
                        echo ("Role ReportingTo User-ID: " . $reportingTo->getId() . "\n");
                        echo ("Role ReportingTo User-Name: " . $reportingTo->getName() . "\n");
                    }
                    echo ("Role AdminUser: ");
                    print_r($role->getAdminUser());
                    echo ("\n");
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
$roleId = "347706117201001";
GetRole::initialize();
GetRole::getRole($roleId);
