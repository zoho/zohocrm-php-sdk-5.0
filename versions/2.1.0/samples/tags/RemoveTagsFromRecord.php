<?php
namespace tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\RecordActionWrapper;
use com\zoho\crm\api\tags\ExistingTag;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\RecordSuccessResponse;
use com\zoho\crm\api\tags\ExistingTagRequestWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class RemoveTagsFromRecord
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
     * <h3> Remove Tags From Record </h3>
     * This method is used to delete the tag associated with a specific record and print the response.
     * @param moduleAPIName - The API Name of the module to remove tag.
     * @param recordId - The ID of the record to be obtained.
     * @param tagNames - The names of the tags to be remove.
     * @throws Exception
     */
    public static function removeTagsFromRecord(string $moduleAPIName, string $recordId)
    {
        $tagsOperations = new TagsOperations();
        $request = new ExistingTagRequestWrapper();
        $tagList = [];
        $tag1 = new ExistingTag();
        $tag1->setName("tagNam3e3e12345");
        array_push($tagList, $tag1);
        $request->setTags($tagList);
        $response = $tagsOperations->removeTags($recordId, $moduleAPIName, $request);
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
                    }
                    else if ($actionResponse instanceof APIException) {
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
            }
            else if ($recordActionHandler instanceof APIException) {
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
$moduleAPIName="leads";
$recordId="34770615623115";
RemoveTagsFromRecord::initialize();
RemoveTagsFromRecord::removeTagsFromRecord($moduleAPIName,$recordId);
