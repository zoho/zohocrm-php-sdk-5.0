<?php
namespace blueprint;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\blueprint\BlueprintOperations;
use com\zoho\crm\api\blueprint\BodyWrapper;
use com\zoho\crm\api\blueprint\APIException;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\blueprint\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateBluePrint
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
     * <h3> Update Blueprint </h3>
     * This method is used to update a single record's Blueprint details with ID and print the response.
     * @param moduleAPIName The API Name of the record's module
     * @param recordId The ID of the record to get Blueprint
     * @param transitionId The ID of the Blueprint transition Id
     * @throws Exception
     */
    public static function updateBlueprint(string $moduleAPIName, string $recordId, string $transitionId)
    {
        //ID of the BluePrint to be updated
        //$transitionId = "3477061173096";
        $bluePrintOperations = new BlueprintOperations($recordId, $moduleAPIName);
        $bodyWrapper = new BodyWrapper();
        $bluePrintList = array();
        $bluePrintClass = 'com\zoho\crm\api\blueprints\BluePrint';
        $bluePrint = new $bluePrintClass();
        $bluePrint->setTransitionId($transitionId);
        $data = new Record();
        $lookup = array();
        $lookup["Phone"] = "8940372937";
        $lookup["id"] = "8940372937";
        // $data->addKeyValue("Lookup_2", $lookup);
        $data->addKeyValue("Phone", "8940372937");
        $data->addKeyValue("Notes", "Updated via blueprint");
        $attachments = array();
        $attachment = array();
        $fileIds = array();
        array_push($fileIds, "blojtd2d13b5f044e4041a3315e0793fb21ef");
        $attachment['$file_id'] = $fileIds;
        array_push($attachments, $attachment);
        $data->addKeyValue("Attachments", $attachments);
        $checkLists = array();
        $list = array();
        $list["list 1"] = true;
        array_push($checkLists, $list);
        $list = array();
        $list["list 2"] = true;
        array_push($checkLists, $list);
        $list = array();
        $list["list 3"] =  true;
        array_push($checkLists, $list);
        $data->addKeyValue("CheckLists", $checkLists);
        $bluePrint->setData($data);
        array_push($bluePrintList, $bluePrint);
        $bodyWrapper->setBlueprint($bluePrintList);
        // var_dump($bodyWrapper);
        $response = $bluePrintOperations->updateBlueprint($bodyWrapper);
        if ($response != null)
        {
            echo ("Status code " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if ($actionResponse instanceof SuccessResponse)
            {
                $successResponse = $actionResponse;
                echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo ("Details: ");
                if ($successResponse->getDetails() != null)
                {
                    foreach ($successResponse->getDetails() as $keyName => $keyValue)
                    {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
            }
            else if ($actionResponse instanceof APIException)
            {
                $exception = $actionResponse;
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
$moduleAPIName="leads";
$recordId="3002165";
$transitionId = "3477061173096";
UpdateBluePrint::initialize();
UpdateBluePrint::updateBlueprint($moduleAPIName,$recordId,$transitionId);
