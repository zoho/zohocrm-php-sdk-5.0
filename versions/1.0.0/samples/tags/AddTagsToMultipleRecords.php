<?php
namespace tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\RecordActionWrapper;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\tags\AddTagsToMultipleRecordsParam;
use com\zoho\crm\api\tags\RecordSuccessResponse;
use com\zoho\crm\api\tags\NewTagRequestWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class AddTagsToMultipleRecords
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
     * <h3> Add Tags To Multiple Records </h3>
     * This method is used to add tags to multiple records simultaneously and print the response.
     * @param moduleAPIName - The API Name of the module to add tags.
     * @param recordIds - The ID of the record to be obtained.
     * @param tagNames - The names of the tags to be add.
     * @throws Exception
     */
    public static function addTagsToMultipleRecords(string $moduleAPIName, array $recordIds, array $tagNames)
    {
        $tagsOperations = new TagsOperations();
        $request = new NewTagRequestWrapper();
        $tagList = [];
        $tag1 = new Tag();
        $tag1->setName("tagNam3e3e12345");
        array_push($tagList, $tag1);
        $request->setTags($tagList);
        $paramInstance = new ParameterMap();
        // foreach($tagNames as $tagName)
        // {
        // 	$paramInstance->add(AddTagsToMultipleRecordsParam::tagNames(), $tagName);
        // }
        $request->setOverWrite(true);
        $request->setIds($recordIds);
        $paramInstance->add(AddTagsToMultipleRecordsParam::overWrite(), "false");
        //Call addTagsToMultipleRecords method that takes paramInstance, moduleAPIName as parameter
        $response = $tagsOperations->addTagsToMultipleRecords($moduleAPIName, $request, $paramInstance);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $recordActionHandler = $response->getObject();
            if ($recordActionHandler instanceof RecordActionWrapper) {
                $recordActionWrapper = $recordActionHandler;
                $actionResponses = $recordActionWrapper->getData();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof RecordSuccessResponse) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($successResponse->getDetails() as $key => $value) {
                            echo ($key . " : ");
                            print_r($value);
                            echo ("\n");
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    } else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
                        echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo ("Code: " . $exception->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        foreach ($exception->getDetails() as $key => $value) {
                            echo ($key . " : " . $value . "\n");
                        }
                        echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                    }
                }
                if ($recordActionWrapper->getLockedCount() != null) {
                    echo ("Locked Count: " . $recordActionWrapper->getLockedCount() . "\n");
                }
                if ($recordActionWrapper->getSuccessCount() != null) {
                    echo ("Success Count: " . $recordActionWrapper->getSuccessCount() . "\n");
                }
                if ($recordActionWrapper->getWfScheduler() != null) {
                    echo ("WF Scheduler: " . $recordActionWrapper->getWfScheduler() . "\n");
                }
            } else if ($recordActionHandler instanceof APIException) {
                $exception = $recordActionHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . " : " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName = "leads";
$recordIds = array("3232323222", 3232323201212);
$tagNames = array("tag1", "tag2");
AddTagsToMultipleRecords::initialize();
AddTagsToMultipleRecords::addTagsToMultipleRecords($moduleAPIName, $recordIds, $tagNames);
