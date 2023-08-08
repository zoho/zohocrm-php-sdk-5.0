<?php
namespace modules;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\modules\Modules;
use com\zoho\crm\api\modules\APIException;
use com\zoho\crm\api\modules\ActionWrapper;
use com\zoho\crm\api\modules\BodyWrapper;
use com\zoho\crm\api\modules\ModulesOperations;
use com\zoho\crm\api\modules\SuccessResponse;
use com\zoho\crm\api\profiles\Profile;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateModuleById
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

    public static function updateModuleById(string $moduleId)
    {
        $moduleOperations = new ModulesOperations();
        $modules = array();
        $profiles = array();
        $profile = new Profile();
        $profile->setId("347706126014");
        // $profile->setDelete(true);
        array_push($profiles, $profile);
        $module = new Modules();
        $module->setProfiles($profiles);
        $module->setAPIName("apiName2");
        array_push($modules, $module);
        $request = new BodyWrapper();
        $request->setModules($modules);
        //Call getModule method that takes BodyWrapper instance and moduleAPIName as parameter
        $response = $moduleOperations->updateModule($moduleId, $request);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getModules();
                foreach ($actionResponses as $actionResponse) {
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
$moduleId="32322032";
UpdateModuleById::initialize();
UpdateModuleById::updateModuleById($moduleId);
