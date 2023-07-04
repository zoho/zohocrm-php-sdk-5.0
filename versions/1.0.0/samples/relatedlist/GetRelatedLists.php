<?php
namespace relatedlist;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\relatedlists\APIException;
use com\zoho\crm\api\relatedlists\RelatedListsOperations;
use com\zoho\crm\api\relatedlists\ResponseWrapper;
use com\zoho\crm\api\relatedlists\GetRelatedListsParam;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class GetRelatedLists
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
     * <h3> Get RelatedLists </h3>
     * This method is used to get the related list data of a particular module and print the response.
     * @param moduleAPIName - The API Name of the module to get related lists
     * @throws Exception
     */
    public static function getRelatedLists(string $moduleAPIName)
    {
        //example, moduleAPIName = "module_api_name";
        $relatedListsOperations = new RelatedListsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetRelatedListsParam::module(), $moduleAPIName);
        //Call getRelatedLists method
        $response = $relatedListsOperations->getRelatedLists($paramInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $relatedLists = $responseWrapper->getRelatedLists();
                foreach ($relatedLists as $relatedList) {
                    echo ("RelatedList SequenceNumber: " . $relatedList->getSequenceNumber() . "\n");
                    echo ("RelatedList DisplayLabel: " . $relatedList->getDisplayLabel() . "\n");
                    echo ("RelatedList APIName: " . $relatedList->getAPIName() . "\n");
                    $module = $relatedList->getModule();
                    if ($module != null) {
                        echo ("RelatedList Module APIName: " . $module->getAPIName() . "\n");
                        echo ("RelatedList Module Id: " . $module->getId() . "\n");
                    }
                    echo ("RelatedList Name: " . $relatedList->getName() . "\n");
                    echo ("RelatedList Action: " . $relatedList->getAction() . "\n");
                    echo ("RelatedList ID: " . $relatedList->getId() . "\n");
                    echo ("RelatedList Href: " . $relatedList->getHref() . "\n");
                    echo ("RelatedList Type: " . $relatedList->getType() . "\n");
                    echo ("RelatedList Connectedmodule: " . $relatedList->getConnectedmodule() . "\n");
                    echo ("RelatedList Linkingmodule: " . $relatedList->getLinkingmodule() . "\n");
                }
            }
            else if ($responseHandler instanceof APIException) {
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
$moduleAPIName = "leads";
GetRelatedLists::initialize();
GetRelatedLists::getRelatedLists($moduleAPIName);
