<?php
namespace pipeline;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\pipeline\PipelineOperations;
use com\zoho\crm\api\pipeline\APIException;
use com\zoho\crm\api\pipeline\BodyWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetPipelines
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
    public static function getPipelines($layoutId)
    {
        $pipelineOperations = new PipelineOperations($layoutId);
        //Call getPipelines method
        $response = $pipelineOperations->getPipelines();
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $pipelines = $responseWrapper->getPipeline();
                foreach ($pipelines as $pipeline) {
                    echo ("Pipeline Id: " . $pipeline->getId() . "\n");
                    echo ("Pipeline Default: ");
                    print_r($pipeline->getDefault());
                    echo ("\n");
                    echo ("Pipeline DisplayValue: " . $pipeline->getDisplayValue() . "\n");
                    echo ("Pipeline Maps ActualValue: " . $pipeline->getActualValue() . "\n");
                    echo ("Pipeline child available  : " . $pipeline->getChildAvailable() . "\n");
                    $parent = $pipeline->getParent();
                    if ($parent != null) {
                        echo ("Pipeline parent ID: " . $parent->getId());
                    }
                    $maps = $pipeline->getMaps();
                    if ($maps != null) {
                        foreach ($maps as $map) {
                            echo ("Pipeline Maps DisplayValue: " . $map->getDisplayValue() . "\n");
                            echo ("Pipeline Maps SequenceNumber: " . $map->getSequenceNumber() . "\n");
                            $forecastCategory = $map->getForecastCategory();
                            if ($forecastCategory != null) {
                                echo ("Pipeline Maps ForecastCategory Name: " . $forecastCategory->getName() . "\n");
                                echo ("Pipeline Maps ForecastCategory Id: " . $forecastCategory->getId() . "\n");
                            }
                            echo ("Pipeline Maps ActualValue: " . $map->getActualValue() . "\n");
                            echo ("Pipeline Maps Id: " . $map->getId() . "\n");
                            echo ("Pipeline Maps ForecastType: " . $map->getForecastType() . "\n");
                            echo ("PickListValue delete" . $map->getDelete() . "\n");
                        }
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: " . "\n");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$layoutId=440248167;
GetPipelines::initialize();
GetPipelines::getPipelines($layoutId);
