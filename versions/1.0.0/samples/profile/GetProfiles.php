<?php
namespace profile;
use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\profiles\APIException;
use com\zoho\crm\api\profiles\ProfileWrapper;
use com\zoho\crm\api\profiles\ProfilesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetProfiles
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

    public static function getProfiles()
    {
        $profilesOperations = new ProfilesOperations();
        $paramInstance = new ParameterMap();
        //Call getProfiles method
        $response = $profilesOperations->getProfiles($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ProfileWrapper) {
                $responseWrapper = $responseHandler;
                $profiles = $responseWrapper->getProfiles();
                foreach ($profiles as $profile) {
                    echo ("Profile DisplayLabel: " . $profile->getDisplayLabel() . "\n");
                    echo ("Profile CreatedTime: ");
                    print_r($profile->getCreatedTime());
                    echo ("\n");
                    echo ("Profile ModifiedTime: ");
                    print_r($profile->getModifiedTime());
                    echo ("\n");
                    echo ("Profile Custom: ");
                    print_r($profile->getCustom());
                    echo ("\n");
                    echo ("Profile Name: " . $profile->getName() . "\n");
                    $modifiedBy = $profile->getModifiedBy();
                    if ($modifiedBy != null) {
                        echo ("Profile Modified By User-ID: " . $modifiedBy->getId() . "\n");
                        echo ("Profile Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo ("Profile Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                    }
                    echo ("Profile Description: " . $profile->getDescription() . "\n");
                    echo ("Profile ID: " . $profile->getId() . "\n");
                    $createdBy = $profile->getCreatedBy();
                    if ($createdBy != null) {
                        echo ("Profile Created By User-ID: " . $createdBy->getId() . "\n");
                        echo ("Profile Created By User-Name: " . $createdBy->getName() . "\n");
                        echo ("Profile Created By User-Email: " . $createdBy->getEmail() . "\n");
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
GetProfiles::initialize();
GetProfiles::getProfiles();
