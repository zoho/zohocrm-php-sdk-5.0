<?php
namespace bulkwrite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\bulkwrite\FileBodyWrapper;
use com\zoho\crm\api\bulkwrite\BulkWriteOperations;
use com\zoho\crm\api\bulkwrite\APIException;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class DownloadBulkWriteResult
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

    public static function downloadBulkWriteResult(string $downloadUrl, string $destinationFolder)
    {
        $bulkWriteOperations = new BulkWriteOperations();
        //Call downloadBulkWriteResult method that takes downloadUrl as parameters
        $response = $bulkWriteOperations->downloadBulkWriteResult($downloadUrl);
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
                if ($exception->getStatus() != null) {
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                }
                if ($exception->getCode() != null) {
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                }
                if ($exception->getDetails() != null) {
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . ": " . $value . "\n");
                    }
                }
                if ($exception->getMessage() != null) {
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
                if ($exception->getXError() != null) {
                    echo ("XError: " . $exception->getXError()->getValue() . "\n");
                }
                if ($exception->getXInfo() != null) {
                    echo ("XInfo: " . $exception->getXInfo()->getValue() . "\n");
                }
                if ($exception->getHttpStatus() != null) {
                    echo ("Message: " . $exception->getHttpStatus() . "\n");
                }
            }
        }
    }
}
$downloadUrl = "https://download-accl.zo.com/v/cm/6735/bulk-write/347009/347009.zip";
$destinationFolder="users/documents";
DownloadBulkWriteResult::initialize();
DownloadBulkWriteResult::downloadBulkWriteResult($downloadUrl,$destinationFolder);
