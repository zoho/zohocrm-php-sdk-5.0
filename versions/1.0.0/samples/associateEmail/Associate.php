<?php
namespace associateEmail;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\associateemail\ActionWrapper;
use com\zoho\crm\api\associateemail\APIException;
use com\zoho\crm\api\associateemail\AssociateEmailOperations;
use com\zoho\crm\api\associateemail\BodyWrapper;
use com\zoho\crm\api\associateemail\Attachments;
use com\zoho\crm\api\associateemail\From;
use com\zoho\crm\api\associateemail\SuccessResponse;
use com\zoho\crm\api\associateemail\To;
use com\zoho\crm\api\associateemail\AssociateEmail;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class Associate
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
    public static function associate($recordId, $module)
    {
        $associateEmailOperations = new AssociateEmailOperations();
        $request = new BodyWrapper();
        $emails = array();
        for ($i=0; $i<1; $i++)
        {
            $associateEmail = new AssociateEmail();
            $from = new From();
            $from->setEmail("abc@zoho.com");
            $from->setUserName("username");
            $associateEmail->setFrom($from);
            $tos = array();
            $to = new To();
            $to->setEmail("abc1@zoho.com");
            $to->setUserName("username1");
            array_push($tos, $to);
            $tos1 = array();
            $to1 = new To();
            $to1->setEmail("abc2@zoho.com");
            $to1->setUserName("user_name2");
            array_push($tos1, $to1);
            $tos2 = array();
            $to2 = new To();
            $to2->setEmail("abc3@zoho.com");
            $to2->setUserName("user_name3");
            array_push($tos2, $to2);
            $associateEmail->setTo($tos);
            $associateEmail->setCc($tos1);
            $associateEmail->setBcc($tos2);
            $associateEmail->setSubject("final");
            $associateEmail->setOriginalMessageId("c6085fae06cbd7b75001d80ffefab46b4ced56218cdc81e69edc5b34aaf40a6b");
            $associateEmail->setDateTime(date_create("2023-06-01T15:32:05")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
            $associateEmail->setSent(true);
            $associateEmail->setContent("<h3><span style=\\\"background-color: rgb(254, 255, 102)\\\">Mail is of rich text format</span></h3><h3><span style=\\\"background-color: rgb(254, 255, 102)\\\">REGARDS,</span></h3><div><span style=\\\"background-color: rgb(254, 255, 102)\\\">AZ</span></div><div><span style=\\\"background-color: rgb(254, 255, 102)\\\">ADMIN</span></div> <div></div>");
            $associateEmail->setMailFormat(new Choice("text"));
            $attachments = array();
            $attachment = new Attachments();
            $attachment->setId("csdsfrfjebjhsdehjdvbsbhhsvdvebdedeferfdjwb");
            array_push($attachments, $attachment);
            array_push($emails, $associateEmail);
        }
        $request->setEmails($emails);
        $response = $associateEmailOperations->associate($recordId, $module, $request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getEmails();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                            echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                            echo ("Details: ");
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : ");
                                print_r($value);
                                echo ("\n");
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        }
                        else if ($actionResponse instanceof APIException) {
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
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
                }
            } else {
                print_r($response);
            }
        }
    }
}
$recordId = 440248001182075;
$moduleAPIName = "leads";
Associate::initialize();
Associate::associate($recordId, $moduleAPIName);
