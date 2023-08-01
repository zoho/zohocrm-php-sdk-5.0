<?php
namespace portalusertype;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\portalusertype\APIException;
use com\zoho\crm\api\portalusertype\PortalUserTypeOperations;
use com\zoho\crm\api\portalusertype\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetUserTypes
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
    public static function getUserTypes(String $portalName)
    {
        $userTypeOperations = new PortalUserTypeOperations($portalName);
        $paramInstance = new ParameterMap();
        $response = $userTypeOperations->getUserTypes($paramInstance);
        if ($response != null) {
            echo("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $userType = $responseWrapper->getUserType();
                if ($userType != null)
                {
                    foreach ($userType as $userType1)
                    {
                        echo("UserType CreatedTime: ");
                        print_r($userType1->getCreatedTime());
                        echo("Usertype Default: " . $userType1->getDefault()). "\n";
                        echo("userType ModofiedTime : ");
                        print_r($userType1->getModifiedTime());
                        $personalityModule = $userType1->getPersonalityModule();
						if ($personalityModule != null)
                        {
                            echo("UserType PersonalityModule ID: " . $personalityModule->getId(). "\n");
                            echo("UserType PersonalityModule APIName: " . $personalityModule->getAPIName(). "\n");

                            echo("UserType PersonalityModule PluralLabel: " . $personalityModule->getPluralLabel(). "\n");
                        }

                        echo("UserType Name: " . $userType1->getName() . "\n");

						$modifiedBy = $userType1->getModifiedBy();
						if ($modifiedBy != null)
                        {
                            echo("UserType ModifiedBy User-ID: " . $modifiedBy->getId() . "\n");
                            echo("UserType ModifiedBy User-Name: " . $modifiedBy->getName() . "\n");
                        }

						echo("UserType Active: " . $userType1->getActive() . "\n");

						echo("UserType Id: " .$userType1->getId() . "\n");

						$createdBy = $userType1->getCreatedBy();
						if ($createdBy != null)
                        {
                            echo("UserType CreatedBy User-ID: " . $createdBy->getId() . "\n");
                            echo("UserType CreatedBy User-Name: " . $createdBy->getName() . "\n");
                        }

						echo("UserType NoOfUsers: " . $userType1->getNoOfUsers() . "\n");
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
$portalName = "PortalsTest101";
GetUserTypes::initialize();
GetUserTypes::getUserTypes($portalName);