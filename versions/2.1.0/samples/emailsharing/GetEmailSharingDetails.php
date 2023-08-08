<?php
namespace emailsharing;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\emailsharing\APIException;
use com\zoho\crm\api\emailsharing\EmailSharingOperations;
use com\zoho\crm\api\emailsharing\ResponseWrapper;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetEmailSharingDetails
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
    public static function GetEmailSharingDetails($recordID, $module)
    {
        $emailsharingOperations = new EmailSharingOperations($recordID, $module);
        $response = $emailsharingOperations->getEmailSharingDetails();
        if ($response != null)
        {
            echo("Status Code: " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204 ? "No Content" : "Not Modified");
                return;
            }
            if ($response->isExpected())
            {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper)
                {
                    $responseWrapper = $responseHandler;
                    $emailSharingDetails = $responseWrapper->getEmailssharingdetails();
                    if ($emailSharingDetails != null)
                    {
                        foreach ($emailSharingDetails as $getemailsharing)
                        {
                            echo("Email_Sharing_Details: " . "\n");
                            $sharefromUsers = $getemailsharing->getShareFromUsers();
                            if ($sharefromUsers != null)
                            {
                                echo("ShareFromUsers : " . "\n");
                                foreach ($sharefromUsers as $sharefromUser)
                                {
                                    echo("id: " . $sharefromUser->getId() . "\n");
                                    echo("name: " . $sharefromUser->getName() . "\n");
                                    echo("type: " . $sharefromUser->getType() . "\n");
                                }
                            }
                            $availableTypes = $getemailsharing->getAvailableTypes();
                            if($availableTypes != null)
                            {
                                echo("Availabletypes: " . "\n");
                                foreach ($availableTypes as $availableType)
                                {
                                    echo($availableType);
                                }
                            }
                        }
                    }
                }
                else if ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
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
}
$recordId = "4402400774074";
$module = "leads";
GetEmailSharingDetails::initialize();
GetEmailSharingDetails::GetEmailSharingDetails($recordId, $module);