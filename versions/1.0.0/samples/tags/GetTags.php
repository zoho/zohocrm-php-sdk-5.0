<?php
namespace tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\ResponseWrapper;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\GetTagsParam;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetTags
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
     * <h3> Get Tags </h3>
     * This method is used to get all the tags in an organization.
     * @param moduleAPIName - The API Name of the module to get tags.
     * @throws Exception
     */
    public static function getTags(string $moduleAPIName)
    {
        $tagsOperations = new TagsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetTagsParam::module(), $moduleAPIName);
        // $paramInstance->add(GetTagsParam::myTags(), ""); //Displays the names of the tags created by the current user.
        //Call getTags method that takes paramInstance as parameter
        $response = $tagsOperations->getTags($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $tags = $responseWrapper->getTags();
                if ($tags != null) {
                    foreach ($tags as $tag) {
                        echo ("Tag CreatedTime: ");
                        print_r($tag->getCreatedTime());
                        echo ("\n");
                        echo ("Tag ModifiedTime: ");
                        print_r($tag->getModifiedTime());
                        echo ("\n");
                        echo ("Tag Name: " . $tag->getName() . "\n");
                        $modifiedBy = $tag->getModifiedBy();
                        if ($modifiedBy != null) {
                            echo ("Tag Modified By User-ID: " . $modifiedBy->getId() . "\n");
                            echo ("Tag Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        }
                        echo ("Tag ID: " . $tag->getId() . "\n");
                        $createdBy = $tag->getCreatedBy();
                        if ($createdBy != null) {
                            echo ("Tag Created By User-ID: " . $createdBy->getId() . "\n");
                            echo ("Tag Created By User-Name: " . $createdBy->getName() . "\n");
                        }
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    echo ("Tag Info Count: " . $info->getCount() . "\n");
                    echo ("Tag Info AllowedCount: " . $info->getAllowedCount() . "\n");
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
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
GetTags::initialize();
GetTags::getTags($moduleAPIName);
