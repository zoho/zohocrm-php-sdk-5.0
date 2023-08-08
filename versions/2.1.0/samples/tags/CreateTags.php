<?php
namespace tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\ActionWrapper;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\BodyWrapper;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\tags\CreateTagsParam;
use com\zoho\crm\api\tags\SuccessResponse;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class CreateTags
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
     * <h3> Create Tags </h3>
     * This method is used to create new tags and print the response.
     * @param moduleAPIName - The API Name of the module to create tags.
     * @throws Exception
     */
    public static function createTags(string $moduleAPIName)
    {
        $tagsOperations = new TagsOperations();
        $request = new BodyWrapper();
        $paramInstance = new ParameterMap();
        $paramInstance->add(CreateTagsParam::module(), $moduleAPIName);
        //List of Tag instances
        $tagList = array();
        $tag1 = new Tag();
        $tag1->setName("PHPTagName1");
        array_push($tagList, $tag1);
        $request->setTags($tagList);
        //Call createTags method that takes BodyWrapper instance and paramInstance as parameter
        $response = $tagsOperations->createTags($request, $paramInstance);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getTags();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof SuccessResponse) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        if ($successResponse->getDetails() != null) {
                            echo ("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : ");
                                print_r($value);
                                echo ("\n");
                            }
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
            } else if ($actionHandler instanceof APIException) {
                $exception = $actionHandler;
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
}
$moduleAPIName = "leads";
CreateTags::initialize();
CreateTags::createTags($moduleAPIName);
