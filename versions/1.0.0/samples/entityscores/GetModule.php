<?php
namespace entityscores;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\entityscores\APIException;
use com\zoho\crm\api\entityscores\EntityScoresOperations;
use com\zoho\crm\api\entityscores\ResponseWrapper;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetModule
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
    public static function getModule($recordId, String $module)
    {
        $entityScoresOperations = new EntityScoresOperations("Positive_Score");
        $response = $entityScoresOperations->getModule($recordId, $module);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $data = $responseWrapper->getData();
                if ($data != null) {
                    foreach ($data as $score) {
                        echo("Score : " . $score->getScore() . "\n");
                        echo("PositiveScore : " . $score->getPositiveScore() . "\n");
                        echo("TouchPointScore : " . $score->getTouchPointScore() . "\n");
                        echo("NegativeScore : " . $score->getNegativeScore() . "\n");
                        echo("touchPointNegativeScore : " . $score->getTouchPointNegativeScore() . "\n");
                        echo("touchPointPositiveScore : " . $score->getTouchPointPositiveScore() . "\n");
                        echo("Id : " . $score->getId() . "\n");
                        echo("ZiaVisions : " . $score->getZiaVisions() . "\n");
                        $scoringRule = $score->getScoringRule();
                        if ($scoringRule != null) {
                            echo("ScoringRule Id : " . $scoringRule->getId() . "\n");
                            echo("ScoringRule Name : " . $scoringRule->getName() . "\n");
                        }
                        $fieldStates = $score->getFieldStates();
                        foreach ($fieldStates as $field) {
                            echo("fieldStates : " . $field . "\n");
                        }
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    if ($info->getPerPage() != null) {
                        echo("Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    if ($info->getCount() != null) {
                        echo("Info Count: " . $info->getCount() . "\n");
                    }
                    if ($info->getPage() != null) {
                        echo("Info Page: " . $info->getPage() . "\n");
                    }
                    if ($info->getMoreRecords() != null) {
                        echo("Info MoreRecords: " . $info->getMoreRecords() . "\n");
                    }
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
$recordId = "4402480774074";
$module = "leads";
GetModule::initialize();
GetModule::getModule($recordId, $module);