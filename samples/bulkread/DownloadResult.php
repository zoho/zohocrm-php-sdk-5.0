<?php
namespace bulkread;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\bulkread\BulkReadOperations;
use com\zoho\crm\api\bulkread\APIException;
use com\zoho\crm\api\bulkread\FileBodyWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DownloadResult
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
     * <h3> Download Result</h3>
     * This method is used to download the Bulkread job as a CSV or an ICS file (only for the Events module).
     * @param jobId The unique ID of the Bulkread job.
     * @param destinationFolder The absolute path where downloaded file has to be stored.
     * @throws Exception
     */
    public static function downloadResult(string $jobId, string $destinationFolder)
    {
        //example
        //String jobId = "34770615177002";
        $bulkReadOperations = new BulkReadOperations();
        //Call downloadResult method that takes jobId as parameters
        $response = $bulkReadOperations->downloadResult($jobId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof FileBodyWrapper) {
                $fileBodyWrapper = $responseHandler;
                $streamWrapper = $fileBodyWrapper->getFile();
                //Create a file instance with the absolute_file_path
                $fp = fopen($destinationFolder . "/" . $streamWrapper->getName(), "w");
                $stream = $streamWrapper->getStream();
                fputs($fp, $stream);
                fclose($fp);
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
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
$jobId = "34770615177002";
$destinationFolder="users/documents";
DownloadResult::initialize();
DownloadResult::downloadResult($jobId,$destinationFolder);
