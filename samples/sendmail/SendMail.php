<?php
namespace sendmail;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\emailtemplates\EmailTemplate;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\sendmail\Attachment;
use com\zoho\crm\api\sendmail\SendMailOperations;
use com\zoho\crm\api\sendmail\Data;
use com\zoho\crm\api\sendmail\From;
use com\zoho\crm\api\sendmail\To;
use com\zoho\crm\api\inventorytemplates\InventoryTemplate;
use com\zoho\crm\api\sendmail\BodyWrapper;
use com\zoho\crm\api\sendmail\APIException;
use com\zoho\crm\api\sendmail\ActionWrapper;
use com\zoho\crm\api\sendmail\SuccessResponse;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class SendMail
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
    public static function sendMail(string $recordId, string $moduleAPIName)
    {
        $sendMailOperations = new SendMailOperations($recordId, $moduleAPIName);
        $mail = new Data();
        $from = new From();
        $from->setUserName("username");
        $from->setEmail("abc@gmail.com");
        $mail->setFrom($from);
        $to = new To();
        $to->setUserName("username1");
        $to->setEmail("abc1@gmail.com");
        $mail->setTo([$to]);
        $userAddressCc = new To();
        $userAddressCc->setUserName("user_name2");
        $userAddressCc->setEmail("abc2@gmail.com");
        $mail->setCc([$userAddressCc]);
        $userAddressBcc = new To();
        $userAddressBcc->setUserName("user_name3");
        $userAddressBcc->setEmail("abc3@gmail.com");
        $mail->setBcc([$userAddressBcc]);
        $userAddressReplyTo = new To();
        $userAddressReplyTo->setUserName("user_name4");
        $userAddressReplyTo->setEmail("abc4@gmail.com");
        $mail->setReplyTo($userAddressReplyTo);
        $attachment = new Attachment();
        $attachment->setId("233dffrbubhbudydh78rd23y4rhebdy3");
        $mail->setAttachments([$attachment]);
        $mail->setOrgEmail(false);
//        $mail->setInReplyTo("2cceafa194d037b63f2181dd8186486f1eb0360aee76d8027e7");
        $mail->setSubject("Mail subject");
        $mail->setScheduledTime(date_create("2023-06-06T15:18:10"));
        $mail->setContent("<br><a href=\"{ConsentForm.en_US}\" id=\"ConsentForm\" class=\"en_US\" target=\"_blank\">Consent form link</a><br><br><br><br><br><h3><span style=\"background-color: rgb(254, 255, 102)\">REGARDS,</span></h3><div><span style=\"background-color: rgb(254, 255, 102)\">AZ</span></div><div><span style=\"background-color: rgb(254, 255, 102)\">ADMIN</span></div> <div></div>");
//        $mail->setConsentEmail(true);
        $mail->setMailFormat(new Choice("html"));
//        $template = new EmailTemplate();
        $template = new InventoryTemplate();
        $template->setId("440248627040");
        $mail->setTemplate($template);
        $wrapper = new BodyWrapper();
        $wrapper->setData([$mail]);
        $response = $sendMailOperations->sendMail($wrapper);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if ($actionHandler instanceof ActionWrapper) {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getData();
                foreach ($actionResponses as $actionResponse) {
                    if ($actionResponse instanceof SuccessResponse) {
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
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
            }
        }
    }
}
$recordId="440248774074";
$moduleAPIName="leads";
SendMail::initialize();
SendMail::sendMail($recordId,$moduleAPIName);