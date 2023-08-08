<?php
namespace portals;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\portals\APIException;
use com\zoho\crm\api\portals\Portals;
use com\zoho\crm\api\portals\PortalsOperations;
use com\zoho\crm\api\portals\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetPortal
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
    public static function getPortal(String $portalName)
    {
        $portalsOperations = new PortalsOperations();
        $response = $portalsOperations->getPortal($portalName);
        if ($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204 ? "NO Content" : "Not Modified");
                return;
            }
            if ($response->isExpected())
            {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper)
                {
                    $responseWrapper = $responseHandler;
                    $portals = $responseWrapper->getPortals();
                    if ($portals != null)
                    {
                        foreach ($portals as $portal)
                        {
                            if ($portal instanceof Portals)
                            {
                                echo("Portals CreatedTime : " . date_format($portal->getCreatedTime(), "d-m-Y-H-i-s") . "\n");
                                echo("Portals ModifiedTime : " . date_format($portal->getModifiedTime(), 'd-m-Y-H-i-s') . "\n");
                                $modifiedBy = $portal->getModifiedBy();
                                if ($modifiedBy != null)
                                {
                                    echo("Portals Modified User-Id : " . $modifiedBy->getId() . "\n");
                                    echo("Portals Modified User-Name : " . $modifiedBy->getName() . "\n");
                                }
                                $createdBy = $portal->getCreatedBy();
                                if ($createdBy != null)
                                {
                                    echo("Portals CreatedBy User-Id : " . $createdBy->getId() . "\n");
                                    echo("Portals CreatedBy User-Name : " . $createdBy->getName() . "\n");
                                }
                                echo("Portals Zaid : " . $portal->getZaid() . "\n");
                                echo("Portals Name : " . $portal->getName() . "\n");
                                echo("Portals Active : " . $portal->getActive() . "\n");
                            }
                        }
                    }
                }
                else if ($responseHandler instanceof APIException)
                {
                    $exception = $responseHandler;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    echo("Details: ");
                    foreach ($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            }
        }
    }
}
$portalName = "PortalsAPItest100";
GetPortal::initialize();
GetPortal::getPortal($portalName);
