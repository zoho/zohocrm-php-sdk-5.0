<?php
namespace fieldmapdependency;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\fieldmapdependency\APIException;
use com\zoho\crm\api\fieldmapdependency\ActionWrapper;
use com\zoho\crm\api\fieldmapdependency\BodyWrapper;
use com\zoho\crm\api\fieldmapdependency\Child;
use com\zoho\crm\api\fieldmapdependency\FieldMapDependencyOperations;
use com\zoho\crm\api\fieldmapdependency\MapDependency;
use com\zoho\crm\api\fieldmapdependency\Parent1;
use com\zoho\crm\api\fieldmapdependency\PickListMapping;
use com\zoho\crm\api\fieldmapdependency\PicklistMap;
use com\zoho\crm\api\fieldmapdependency\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateMapDependency
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
    public static function updateMapDependency(string $layoutId, string $module, string $dependencyId)
    {
        $fieldMapDependencyOperations = new FieldMapDependencyOperations($layoutId, $module);
        $bodyWrapper = new BodyWrapper();
        $mapDependencies = array();
        $mapdependency = new MapDependency();
        $parent = new Parent1();
        $parent->setAPIName("Lead_Status");
        $parent->setId("3652397002611");
        $mapdependency->setParent($parent);
        $child = new Child();
        $child->setAPIName("Lead_Status");
        $child->setId("3652397002611");
        $mapdependency->setChild($child);
        $pickListValues = array();
        $pickListValue = new PickListMapping();
        $pickListValue->setDisplayValue("-None-");
        $pickListValue->setId("3652397003409");
        $pickListValue->setActualValue("-None-");
        $picklistMaps = array();
        $picklistMap = new PicklistMap();
        $picklistMap->setId("3652397003389");
        $picklistMap->setActualValue("Cold Call");
        $picklistMap->setDisplayValue("Cold Call");
        array_push($picklistMaps, $picklistMap);
        $picklistMap = new PicklistMap();
        $picklistMap->setId("3652397003391");
        $picklistMap->setActualValue("-None-");
        $picklistMap->setDisplayValue("-None-");
        array_push($picklistMaps, $picklistMap);
        $pickListValue->setMaps($picklistMaps);
        array_push($pickListValues, $pickListValue);
        $mapdependency->setPickListValues($pickListValues);
        array_push($mapDependencies, $mapdependency);
        $bodyWrapper->setMapDependency($mapDependencies);
        $response = $fieldMapDependencyOperations->updateMapDependency($dependencyId, $bodyWrapper);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getMapDependency();
                if ($actionResponses != null) {
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
$layoutId="3002010222001";
$module="leads";
$dependencyId="30031";
UpdateMapDependency::initialize();
UpdateMapDependency::updateMapDependency($layoutId,$module,$dependencyId);
