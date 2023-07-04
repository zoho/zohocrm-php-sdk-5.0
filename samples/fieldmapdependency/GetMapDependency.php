<?php
namespace fieldmapdependency;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\fieldmapdependency\APIException;
use com\zoho\crm\api\fieldmapdependency\BodyWrapper;
use com\zoho\crm\api\fieldmapdependency\FieldMapDependencyOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetMapDependency
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
    public static function getMapDependency(string $layoutId, string $module, string $dependencyId)
    {
        $fieldMapDependencyOperations = new FieldMapDependencyOperations($layoutId, $module);
        $response = $fieldMapDependencyOperations->getMapDependency($dependencyId);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $mapDependencies = $responseWrapper->getMapDependency();
                if ($mapDependencies != null) {
                    foreach ($mapDependencies as $mapDependency) {
                        $parent = $mapDependency->getParent();
                        if ($parent != null) {
                            echo ("MapDependency Parent ID: " . $parent->getId() . "\n");
                            echo ("MapDependency Parent APIName: " . $parent->getAPIName() . "\n");
                        }
                        $child = $mapDependency->getChild();
                        if ($child != null) {
                            echo ("MapDependency Child ID: " . $child->getId() . "\n");
                            echo ("MapDependency Child APIName: " . $child->getAPIName() . "\n");
                        }
                        $pickListValues = $mapDependency->getPickListValues();
                        if ($pickListValues != null) {
                            foreach ($pickListValues as $pickListValue) {
                                echo ("MapDependency PickListValue ID: " . $pickListValue->getId() . "\n");
                                echo ("MapDependency PickListValue ActualValue: " . $pickListValue->getActualValue() . "\n");
                                echo ("MapDependency PickListValue DisplayValue: " . $pickListValue->getDisplayValue() . "\n");
                                $picklistMaps = $pickListValue->getMaps();
                                if ($picklistMaps != null) {
                                    foreach ($picklistMaps as $picklistMap) {
                                        echo ("MapDependency PickListValue Map ID: " . $picklistMap->getId() . "\n");
                                        echo ("MapDependency PickListValue Map ActualValue: " . $picklistMap->getActualValue() . "\n");
                                        echo ("MapDependency PickListValue Map DisplayValue: " . $picklistMap->getDisplayValue() . "\n");
                                    }
                                }
                            }
                        }
                        echo ("MapDependency Internal: " . $mapDependency->getInternal() . "\n");
                        echo ("MapDependency Active: " . $mapDependency->getActive() . "\n");
                        echo ("MapDependency ID: " . $mapDependency->getId() . "\n");
                        echo ("MapDependency Active: " . $mapDependency->getSource() . "\n");
                        echo ("MapDependency Category: " . $mapDependency->getCategory() . "\n");
                        echo ("MapDependency Source: " . $mapDependency->getSource() . "\n");
                    }
                    $info = $responseWrapper->getInfo();
                    if ($info != null) {
                        if ($info->getPerPage() != null) {
                            echo ("MapDependency Info PerPage: " . $info->getPerPage() . "\n");
                        }
                        if ($info->getCount() != null) {
                            echo ("MapDependency Info Count: " . $info->getCount() . "\n");
                        }
                        if ($info->getPage() != null) {
                            echo ("MapDependency Info Page: " . $info->getPage() . "\n");
                        }
                        if ($info->getMoreRecords() != null) {
                            echo ("MapDependency Info MoreRecords: " . $info->getMoreRecords() . "\n");
                        }
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
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
$layoutId="3002010222001";
$module="leads";
$dependencyId="3031";
GetMapDependency::initialize();
GetMapDependency::getMapDependency($layoutId,$module,$dependencyId);
