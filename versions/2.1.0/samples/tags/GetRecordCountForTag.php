<?php
namespace tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\GetRecordCountForTagParam;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\CountResponseWrapper;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class GetRecordCountForTag
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
     * <h3> Get Record Count For Tag </h3>
     * This method is used to get the total number of records under a tag and print the response.
     * @param moduleAPIName - The API Name of the module.
     * @param tagId - The ID of the tag to be obtained.
     * @throws Exception
     */
    public static function getRecordCountForTag(string $moduleAPIName, string $tagId)
    {
        $tagsOperations = new TagsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetRecordCountForTagParam::module(), $moduleAPIName);
        //Call getRecordCountForTag method that takes paramInstance and tagId as parameter
        $response = $tagsOperations->getRecordCountForTag($tagId, $paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $countHandler = $response->getObject();
            if ($countHandler instanceof CountResponseWrapper) {
                $countWrapper = $countHandler;
                echo ("Tag Count: " . $countWrapper->getCount() . "\n");
            }
            else if ($countHandler instanceof APIException) {
                $exception = $countHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName="leads";
$tagId="347706118964022";
GetRecordCountForTag::initialize();
GetRecordCountForTag::getRecordCountForTag($moduleAPIName,$tagId);
