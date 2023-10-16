<?php
namespace pipeline;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\pipeline\TPipeline;
use com\zoho\crm\api\pipeline\PipelineOperations;
use com\zoho\crm\api\pipeline\TransferPipelineWrapper;
use com\zoho\crm\api\pipeline\TransferSuccess;
use com\zoho\crm\api\pipeline\Stages;
use com\zoho\crm\api\pipeline\TransferPipeLine;
use com\zoho\crm\api\pipeline\APIException;
use com\zoho\crm\api\pipeline\TransferPipelineActionWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class TransferAndDelete
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
    public static function transferAndDelete($layoutId)
    {
        $pipelineOperations = new PipelineOperations($layoutId);
        $transferAndDeleteWrapper = new TransferPipelineWrapper();
        $transferPipeLine = new TransferPipeLine();
        $pipeline = new TPipeline();
        $pipeline->setFrom("3477061016634118");
        $pipeline->setTo("3477061009599012");
        $transferPipeLine->setPipeline($pipeline);
        $stage = new Stages();
        $stage->setFrom("3652397006817");
        $stage->setTo("3652397006819");
        $transferPipeLine->setStages([$stage]);
        $transferAndDeleteWrapper->setTransferPipeline([$transferPipeLine]);
        //Call transferAndDelete method
        $response = $pipelineOperations->transferPipelines($transferAndDeleteWrapper);
        if ($response != null) {
            echo ("Status code" . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof TransferPipelineActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getTransferPipeline();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof TransferSuccess) {
                        $successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        if ($successResponse->getDetails() != null) {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue) {
                                echo ($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                    }
                    else if ($actionResponse instanceof APIException) {
                        $exception = $actionResponse;
                        echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo ("Code: " . $exception->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        if ($exception->getDetails() != null) {
                            foreach ($exception->getDetails() as $keyName => $keyValue) {
                                echo ($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                    }
                }
            }
            else if ($actionHandler instanceof APIException) {
                $exception = $actionHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                if ($exception->getDetails() != null) {
                    echo ("Details: \n");
                    foreach ($exception->getDetails() as $keyName => $keyValue) {
                        echo ($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$layoutId="3023222332";
TransferAndDelete::initialize();
TransferAndDelete::transferAndDelete($layoutId);
