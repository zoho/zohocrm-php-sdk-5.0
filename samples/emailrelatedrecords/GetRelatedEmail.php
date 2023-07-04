<?php
namespace emailrelatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\emailrelatedrecords\APIException;
use com\zoho\crm\api\emailrelatedrecords\Email;
use com\zoho\crm\api\emailrelatedrecords\EmailRelatedRecordsOperations;
use com\zoho\crm\api\emailrelatedrecords\ResponseWrapper;
use com\zoho\crm\api\emailrelatedrecords\Status;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetRelatedEmail
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
    public static function getRelatedEmail($moduleAPIName , $id, $messageId)
    {
        $emailTemplatesOperations = new EmailRelatedRecordsOperations($id, $moduleAPIName, null, null);
        $response = $emailTemplatesOperations->getEmailsRelatedRecord($messageId);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper) {
                $responseWrapper = $responseHandler;
                $emailTemplates = $responseWrapper->getEmails();
                foreach ($emailTemplates as $emailTemplate) {
                    if ($emailTemplate instanceof Email)
                    $userDetails = $emailTemplate->getCc();
                    if ($userDetails != null) {
                        foreach ($userDetails as $userDetail) {
                            echo ("GetRelatedEmail User Email: " . $userDetail->getEmail() . "\n");
                            echo ("GetRelatedEmail User Name: " . $userDetail->getUserName() . "\n");
                        }
                    }
                    echo ("GetRelatedEmail Summary: " . $emailTemplate->getSummary() . "\n");
                    $owner = $emailTemplate->getOwner();
                    if ($owner != null) {
                        echo ("GetRelatedEmail User ID: " . $owner->getId() . "\n");
                        echo ("GetRelatedEmail User Name: " . $owner->getName() . "\n");
                    }
                    echo ("GetRelatedEmail Read: " . $emailTemplate->getRead() . "\n");
                    echo ("GetRelatedEmail Sent: " . $emailTemplate->getSent() . "\n");
                    echo ("GetRelatedEmail Subject: " . $emailTemplate->getSubject() . "\n");
                    echo ("GetRelatedEmail Intent: " . $emailTemplate->getIntent() . "\n");
                    echo ("GetRelatedEmail Content: " . $emailTemplate->getContent() . "\n");
                    echo ("GetRelatedEmail SentimentInfo: " . $emailTemplate->getSentimentInfo() . "\n");
                    echo ("GetRelatedEmail MessageId: " . $emailTemplate->getMessageId() . "\n");
                    echo ("GetRelatedEmail MessageId: " . $emailTemplate->getSource() . "\n");
                    $linkedRecord = $emailTemplate->getLinkedRecord();
                    if ($linkedRecord != null) {
                        echo ("GetRelatedEmail LinkedRecord id : " . $linkedRecord->getId() . "\n");
                        $module = $linkedRecord->getModule();
                        if ($module != null) {
                            echo ("GetRelatedEmail LinkedRecord Module APIName : " . $module->getAPIName() . "\n");
                            echo ("GetRelatedEmail LinkedRecord Module Id : " . $module->getId() . "\n");
                        }
                    }
                    $attachments = $emailTemplate->getAttachments();
                    if ($attachments != null)
                    {
                        foreach ($attachments as $attachment)
                        {
                            echo ("EmailRelatedRecords Attachment Size : " . $attachment->getSize());
                            echo ("EmailRelatedRecords Attachment Name : " . $attachment->getName());
                            echo("EmailRelatedRecords Attachment id : " . $attachment->getId());
                        }
                    }
                    echo ("GetRelatedEmail Emotion: " . $emailTemplate->getEmotion() . "\n");
                    $from = $emailTemplate->getFrom();
                    if ($from != null) {
                        echo ("GetRelatedEmail From User Email: " . $from->getEmail() . "\n");
                        echo ("GetRelatedEmail From User Name: " . $from->getUserName() . "\n");
                    }
                    $toUserDetails = $emailTemplate->getTo();
                    if ($toUserDetails != null) {
                        foreach ($toUserDetails as $userDetail) {
                            echo ("GetRelatedEmail User Email: " . $userDetail->getEmail() . "\n");
                            echo ("GetRelatedEmail User Name: " . $userDetail->getUserName() . "\n");
                        }
                    }
                    echo ("GetRelatedEmail Time: ");
                    print_r($emailTemplate->getTime());
                    echo ("\n");
                    $status = $emailTemplate->getStatus();
                    if ($status != null) {
                        foreach ($status as $status1) {
                            if ($status1 instanceof Status)
                            echo ("GetRelatedEmail Status Type: " . $status1->getType() . "\n");
                            echo ("GetRelatedEmail Status Name: " . date_format($status1->getBouncedTime(), 'd-m-Y-H-i-s' ). "\n");
                            echo ("GetRelatedEmail Status Name: " . $status1->getBouncedReason() . "\n");
                        }
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    if ($info->getCount() != null) {
                        echo ("Record Info Count: " . $info->getCount() . "\n");
                    }
                    if ($info->getNextIndex() != null) {
                        echo ("Record Info NextIndex: " . $info->getNextIndex() . "\n");
                    }
                    if ($info->getPrevIndex() != null) {
                        echo ("Record Info PrevIndex: " . $info->getPrevIndex() . "\n");
                    }
                    if ($info->getPerPage() != null) {
                        echo ("Record Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    if ($info->getMoreRecords() != null) {
                        echo ("Record Info MoreRecords: " . $info->getMoreRecords() . "\n");
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
$moduleAPIName = "leads";
$id = "4402480774074";
$messageId = "c6085fae06cbd7b75ffefab46b4ced56218cdc81e69edc5b34aaf40a6b";
GetRelatedEmail::initialize();
GetRelatedEmail::getRelatedEmail($moduleAPIName, $id, $messageId);