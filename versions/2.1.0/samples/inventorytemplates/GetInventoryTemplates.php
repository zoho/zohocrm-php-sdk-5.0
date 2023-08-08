<?php
namespace inventorytemplates;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\inventorytemplates\InventoryTemplatesOperations;
use com\zoho\crm\api\inventorytemplates\ResponseWrapper;
use com\zoho\crm\api\inventorytemplates\APIException;
use com\zoho\crm\api\inventorytemplates\GetInventoryTemplatesParam;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetInventoryTemplates
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
    public static function getInventoryTemplates(string $module = null)
    {
        $inventoryTemplatesOperations = new InventoryTemplatesOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetInventoryTemplatesParam::module(), $module);
        $response = $inventoryTemplatesOperations->getInventoryTemplates($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $inventoryTemplates = $responseWrapper->getInventoryTemplates();
                foreach ($inventoryTemplates as $inventoryTemplate) {
                    echo ("InventoryTemplate CreatedTime: ");
                    print_r($inventoryTemplate->getCreatedTime());
                    echo ("\n");
                    $module = $inventoryTemplate->getModule();
                    if ($module != null) {
                        echo ("InventoryTemplate Module Name : " . $module->getAPIName() . "\n");
                        echo ("InventoryTemplate Module Id : " . $module->getId() . "\n");
                    }
                    echo ("InventoryTemplate Type: " . $inventoryTemplate->getType() . "\n");
                    $createdBy = $inventoryTemplate->getCreatedBy();
                    if ($createdBy != null) {
                        echo ("InventoryTemplate Created By User-ID: " . $createdBy->getId() . "\n");
                        echo ("InventoryTemplate Created By User-Name: " . $createdBy->getName() . "\n");
                    }
                    echo ("InventoryTemplate ModifiedTime: ");
                    print_r($inventoryTemplate->getModifiedTime());
                    echo ("\n");
                    $folder = $inventoryTemplate->getFolder();
                    if ($folder != null) {
                        echo ("InventoryTemplate Folder Id: " . $folder->getId() . "\n");
                        echo ("InventoryTemplate Folder Name: " . $folder->getName() . "\n");
                    }
                    echo ("InventoryTemplate LastUsageTime: ");
                    print_r($inventoryTemplate->getLastUsageTime());
                    echo ("\n");
                    echo ("InventoryTemplate Name: " . $inventoryTemplate->getName() . "\n");
                    $modifiedBy = $inventoryTemplate->getModifiedBy();
                    if ($modifiedBy != null) {
                        echo ("InventoryTemplate Modified By User-ID: " . $modifiedBy->getId() . "\n");
                        echo ("InventoryTemplate Modified By User-Name: " . $modifiedBy->getName() . "\n");
                    }
                    echo ("InventoryTemplate Id: " . $inventoryTemplate->getId() . "\n");
                    echo ("InventoryTemplate EditorMode: " . $inventoryTemplate->getEditorMode() . "\n");
                    echo ("InventoryTemplate Content: " . $inventoryTemplate->getContent() . "\n");
                    echo ("InventoryTemplate Favorite: ");
                    print_r($inventoryTemplate->getFavorite());
                    echo ("\n");
                }
                $info = $responseWrapper->getInfo();
                echo ("InventoryTemplate Info PerPage : " . $info->getPerPage() . "\n");
                echo ("InventoryTemplate Info Count : " . $info->getCount() . "\n");
                echo ("InventoryTemplate Info Page : " . $info->getPage() . "\n");
                echo ("InventoryTemplate Info MoreRecords : ");
                print_r($info->getMoreRecords());
                echo ("\n");
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                if ($exception->getDetails() != null) {
                    foreach ($exception->getDetails() as $keyName => $keyValue) {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$module="Quotes";
GetInventoryTemplates::initialize();
GetInventoryTemplates::getInventoryTemplates($module);
