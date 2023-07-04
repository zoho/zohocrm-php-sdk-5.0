<?php
namespace relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\layouts\Layout;
use com\zoho\crm\api\record\FileDetails;
use com\zoho\crm\api\record\LineTax;
use com\zoho\crm\api\record\PricingDetails;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\GetRelatedRecordsParam;
use com\zoho\crm\api\relatedrecords\ResponseWrapper;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\users\User;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\Consent;
use com\zoho\crm\api\record\Comment;
use com\zoho\crm\api\record\Participants;
use com\zoho\crm\api\attachments\Attachment;
use com\zoho\crm\api\record\ImageUpload;

require_once "vendor/autoload.php";

class GetRelatedRecords
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
     * <h3> Get Related Records </h3>
     * This method is used to get the related list records and print the response.
     * @param moduleAPIName - The API Name of the module to get related records.
     * @param recordId - The ID of the record to be obtained.
     * @param relatedListAPIName - The API name of the related list. To get the API name of the related list.
     * @throws Exception
     */
    public static function getRelatedRecords(string $moduleAPIName, string $recordId, string $relatedListAPIName)
    {
        //example
        //moduleAPIName = "module_api_name";
        //$recordId = "34002";
        //$relatedListAPIName = "module_api_name";
        $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName, null);
        $paramInstance = new ParameterMap();
        // $paramInstance->add(GetRelatedRecordsParam::page(), 1);
        // $paramInstance->add(GetRelatedRecordsParam::perPage(), 2);
        $paramInstance->add(GetRelatedRecordsParam::fields(), "id");
        $headerInstance = new HeaderMap();
        $datetime = date_create("2019-02-26T15:28:34+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        // $headerInstance->add(GetRelatedRecordsHeader::IfModifiedSince(), $datetime);
        //Call getRelatedRecords method that takes recordId, paramInstance and headerInstance as parameter
        $response = $relatedRecordsOperations->getRelatedRecords($recordId, $paramInstance, $headerInstance);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrapper = $responseHandler;
                    $records = $responseWrapper->getData();
                    if ($records != null) {
                        foreach ($records as $record) {
                            echo ("RelatedRecord ID: " . $record->getId() . "\n");
                            $createdBy = $record->getCreatedBy();
                            if ($createdBy != null) {
                                echo ("RelatedRecord Created By User-ID: " . $createdBy->getId() . "\n");
                                echo ("RelatedRecord Created By User-Name: " . $createdBy->getName() . "\n");
                                echo ("RelatedRecord Created By User-Email: " . $createdBy->getEmail() . "\n");
                            }
                            echo ("RelatedRecord CreatedTime: ");
                            print_r($record->getCreatedTime());
                            echo ("\n");
                            $modifiedBy = $record->getModifiedBy();
                            if ($modifiedBy != null) {
                                echo ("RelatedRecord Modified By User-ID: " . $modifiedBy->getId() . "\n");
                                echo ("RelatedRecord Modified By User-Name: " . $modifiedBy->getName() . "\n");
                                echo ("RelatedRecord Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                            }
                            echo ("RelatedRecord ModifiedTime: ");
                            print_r($record->getModifiedTime());
                            echo ("\n");
                            $tags = $record->getTag();
                            if ($tags != null) {
                                foreach ($tags as $tag) {
                                    echo ("RelatedRecord Tag Name: " . $tag->getName() . "\n");
                                    echo ("RelatedRecord Tag ID: " . $tag->getId() . "\n");
                                }
                            }
                            //To get particular field value
                            echo ("RelatedRecord Field Value: " . $record->getKeyValue("Last_Name") . "\n"); // FieldApiName
                            echo ("RelatedRecord KeyValues : \n");
                            foreach ($record->getKeyValues() as $keyName => $value) {
                                if ($value != null) {
                                    if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                        if ($value[0] instanceof FileDetails) {
                                            $fileDetails = $value;
                                            foreach ($fileDetails as $fileDetail) {
                                                echo ("Record FileDetails FileIds: " . $fileDetail->getFileIdS() . "\n");
                                                echo ("Record FileDetails FileNameS: " . $fileDetail->getFileNameS() . "\n");
                                                echo ("Record FileDetails SizeS: " . $fileDetail->getSizeS() . "\n");
                                                echo ("Record FileDetails ID: " . $fileDetail->getId() . "\n");
                                            }
                                        } else if ($value[0] instanceof Tag) {
                                            $tagList = $value;
                                            foreach ($tagList as $tag) {
                                                echo ("RelatedRecord Tag Name: " . $tag->getName() . "\n");
                                                echo ("RelatedRecord Tag ID: " . $tag->getId() . "\n");
                                            }
                                        } else if ($value[0] instanceof PricingDetails) {
                                            $pricingDetails = $value;
                                            foreach ($pricingDetails as $pricingDetail) {
                                                echo ("RelatedRecord PricingDetails ToRange: " . $pricingDetail->getToRange() . "\n");
                                                echo ("RelatedRecord PricingDetails Discount: " . $pricingDetail->getDiscount() . "\n");
                                                echo ("RelatedRecord PricingDetails ID: " . $pricingDetail->getId() . "\n");
                                                echo ("RelatedRecord PricingDetails FromRange: " . $pricingDetail->getFromRange() . "\n");
                                            }
                                        } else if ($value[0] instanceof Participants) {
                                            $participants = $value;
                                            foreach ($participants as $participant) {
                                                echo ("RelatedRecord Participants Name: " . $participant->getName() . "\n");
                                                echo ("RelatedRecord Participants Invited: " . $participant->getInvited() . "\n");
                                                echo ("RelatedRecord Participants ID: " . $participant->getId() . "\n");
                                                echo ("RelatedRecord Participants Type: " . $participant->getType() . "\n");
                                                echo ("RelatedRecord Participants Participant: " . $participant->getParticipant() . "\n");
                                                echo ("RelatedRecord Participants Status: " . $participant->getStatus() . "\n");
                                            }
                                        } else if ($value[0] instanceof Record) {
                                            $recordList = $value;
                                            foreach ($recordList as $record1) {
                                                foreach ($record1->getKeyValues() as $key => $value1) {
                                                    echo ($key . " : ");
                                                    print_r($value1);
                                                    echo ("\n");
                                                }
                                            }
                                        } else if ($value[0] instanceof LineTax) {
                                            $lineTaxes = $value;
                                            foreach ($lineTaxes as $lineTax) {
                                                echo ("RelatedRecord ProductDetails LineTax Percentage: " . $lineTax->getPercentage() . "\n");
                                                echo ("RelatedRecord ProductDetails LineTax Name: " . $lineTax->getName() . "\n");
                                                echo ("RelatedRecord ProductDetails LineTax Id: " . $lineTax->getId() . "\n");
                                                echo ("RelatedRecord ProductDetails LineTax Value: " . $lineTax->getValue() . "\n");
                                            }
                                        } else if ($value[0] instanceof Choice) {
                                            $choice = $value;
                                            foreach ($choice as $choiceValue) {
                                                echo ("RelatedRecord " . $keyName . " : " . $choiceValue->getValue() . "\n");
                                            }
                                        } else if ($value[0] instanceof Comment) {
                                            $comments = $value;
                                            foreach ($comments as $comment) {
                                                echo ("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");
                                                echo ("Record Comment CommentedTime: ");
                                                print_r($comment->getCommentedTime());
                                                echo ("\n");
                                                echo ("Record Comment CommentContent: " . $comment->getCommentContent() . "\n");
                                                echo ("Record Comment Id: " . $comment->getId() . "\n");
                                            }
                                        } else if ($value[0] instanceof Attachment) {
                                            $attachments = $value;
                                            foreach ($attachments as $attachment) {
                                                $owner = $attachment->getOwner();
                                                if ($owner != null) {
                                                    echo ("RelatedRecord Attachment Owner User-Name: " . $owner->getName() . "\n");
                                                    echo ("RelatedRecord Attachment Owner User-ID: " . $owner->getId() . "\n");
                                                    echo ("RelatedRecord Attachment Owner User-Email: " . $owner->getEmail() . "\n");
                                                }
                                                echo ("RelatedRecord Attachment Modified Time: ");
                                                print_r($attachment->getModifiedTime());
                                                echo ("\n");
                                                echo ("RelatedRecord Attachment File Name: " . $attachment->getFileName() . "\n");
                                                echo ("RelatedRecord Attachment Created Time: ");
                                                print_r($attachment->getCreatedTime());
                                                echo ("\n");
                                                echo ("RelatedRecord Attachment File Size: " . $attachment->getSize() . "\n");
                                                $parentId = $attachment->getParentId();
                                                if ($parentId != null) {
                                                    echo ("RelatedRecord Attachment parent record Name: " . $parentId->getName() . "\n");
                                                    echo ("RelatedRecord Attachment parent record ID: " . $parentId->getId() . "\n");
                                                }
                                                echo ("RelatedRecord Attachment is Editable: " . $attachment->getEditable() . "\n");
                                                echo ("RelatedRecord Attachment File ID: " . $attachment->getFileId() . "\n");
                                                echo ("RelatedRecord Attachment File Type: " . $attachment->getType() . "\n");
                                                echo ("RelatedRecord Attachment seModule: " . $attachment->getSeModule() . "\n");
                                                $modifiedBy = $attachment->getModifiedBy();
                                                if ($modifiedBy != null) {
                                                    echo ("RelatedRecord Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");
                                                    echo ("RelatedRecord Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");
                                                    echo ("RelatedRecord Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                                                }
                                                echo ("RelatedRecord Attachment State: " . $attachment->getState() . "\n");
                                                echo ("RelatedRecord Attachment ID: " . $attachment->getId() . "\n");
                                                $createdBy = $attachment->getCreatedBy();
                                                if ($createdBy != null) {
                                                    echo ("RelatedRecord Attachment Created By User-Name: " . $createdBy->getName() . "\n");
                                                    echo ("RelatedRecord Attachment Created By User-ID: " . $createdBy->getId() . "\n");
                                                    echo ("RelatedRecord Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
                                                }
                                                echo ("RelatedRecord Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
                                            }
                                        } else if ($value[0] instanceof ImageUpload) {
                                            $imageUploads = $value;
                                            foreach ($imageUploads as $imageUpload) {
                                                echo ("RelatedRecord " . $keyName . " Description: " . $imageUpload->getDescriptionS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " FileIds: " . $imageUpload->getFileIdS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " FileNameS: " . $imageUpload->getFileNameS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " PreviewIdS: " . $imageUpload->getPreviewIdS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " SizeS: " . $imageUpload->getSizeS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " States: " . $imageUpload->getStateS() . "\n");
                                                echo ("RelatedRecord " . $keyName . " ID: " . $imageUpload->getId() . "\n");
                                                echo ("RelatedRecord " . $keyName . " SequenceNumberS: " . $imageUpload->getSequenceNumberS() . "\n");
                                            }
                                        } else {
                                            echo ($keyName . " : ");
                                            print_r($value);
                                            echo ("\n");
                                        }
                                    } else if ($value instanceof Layout) {
                                        $layout = $value;
                                        if ($layout != null) {
                                            echo ("RelatedRecord " . $keyName . " ID: " . $layout->getId() . "\n");
                                            echo ("RelatedRecord " . $keyName . " Name: " . $layout->getName() . "\n");
                                        }
                                    } else if ($value instanceof User) {
                                        $user = $value;
                                        if ($user != null) {
                                            echo ("RelatedRecord " . $keyName . " User-ID: " . $user->getId() . "\n");
                                            echo ("RelatedRecord " . $keyName . " User-Name: " . $user->getName() . "\n");
                                            echo ("RelatedRecord " . $keyName . " User-Email: " . $user->getEmail() . "\n");
                                        }
                                    } else if ($value instanceof Choice) {
                                        $choiceValue = $value;
                                        echo ("RelatedRecord " . $keyName . " : " . $choiceValue->getValue() . "\n");
                                    } else if ($value instanceof Record) {
                                        $recordValue = $value;
                                        echo ("RelatedRecord " . $keyName . " ID: " . $recordValue->getId() . "\n");
                                        echo ("RelatedRecord " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
                                    } else if ($value instanceof Consent) {
                                        $consent = $value;
                                        echo ("RelatedRecord Consent ID: " . $consent->getId());
                                        $owner = $consent->getOwner();
                                        if ($owner != null) {
                                            echo ("RelatedRecord Consent Owner Name: " . $owner->getName());
                                            echo ("RelatedRecord Consent Owner ID: " . $owner->getId());
                                            echo ("RelatedRecord Consent Owner Email: " . $owner->getEmail());
                                        }
                                        $consentCreatedBy = $consent->getCreatedBy();
                                        if ($consentCreatedBy != null) {
                                            echo ("RelatedRecord Consent CreatedBy Name: " . $consentCreatedBy->getName());
                                            echo ("RelatedRecord Consent CreatedBy ID: " . $consentCreatedBy->getId());
                                            echo ("RelatedRecord Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
                                        }
                                        $consentModifiedBy = $consent->getModifiedBy();
                                        if ($consentModifiedBy != null) {
                                            echo ("RelatedRecord Consent ModifiedBy Name: " . $consentModifiedBy->getName());
                                            echo ("RelatedRecord Consent ModifiedBy ID: " . $consentModifiedBy->getId());
                                            echo ("RelatedRecord Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
                                        }
                                        echo ("Record Consent CreatedTime: " . date_format($consent->getCreatedTime(), 'd-m-y-H-i-s') . "\n");
                                        echo ("Record Consent ModifiedTime: " . date_format($consent->getModifiedTime(), 'd-m-y-H-i-s') . "\n");
                                        echo ("Record Consent ContactThroughEmail: " . $consent->getContactThroughEmail());
                                        echo ("Record Consent ContactThroughSocial: " . $consent->getContactThroughSocial());
                                        echo ("Record Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());
                                        echo ("Record Consent ContactThroughPhone: " . $consent->getContactThroughPhone());
                                        echo ("Record Consent MailSentTime: " . date_format($consent->getMailSentTime(), 'd-m-y-H-i-s') . "\n");
                                        echo ("Record Consent ConsentDate: " . date_format($consent->getConsentDate(), 'd-m-y-H-i-s') . "\n");
                                        echo ("RelatedRecord Consent ConsentRemarks: " . $consent->getConsentRemarks());
                                        echo ("RelatedRecord Consent ConsentThrough: " . $consent->getConsentThrough());
                                        echo ("RelatedRecord Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());
                                        //To get custom values
                                        echo ("RelatedRecord Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
                                    } else {
                                        echo ($keyName . " : ");
                                        print_r($value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ($keyName . " : ");
                                    print_r($value);
                                    echo ("\n");
                                }
                            }
                        }
                    }
                    $info = $responseWrapper->getInfo();
                    if ($info != null) {
                        echo ("RelatedRecord Info PerPage: " . $info->getPerPage() . "\n");
                        echo ("RelatedRecord Info Count: " . $info->getCount() . "\n");
                        echo ("RelatedRecord Info Page: " . $info->getPage() . "\n");
                        echo ("RelatedRecord Info MoreRecords: ");
                        print_r($info->getMoreRecords());
                        echo ("\n");
                    }
                }
                else if ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    if ($exception->getDetails() != null) {
                        echo ("Details: ");
                        foreach ($exception->getDetails() as $key => $value) {
                            echo ($key . " : " . $value . "\n");
                        }
                        echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                    }
                }
            } else {
                print_r($response);
            }
        }
    }
}
$moduleAPIName = "Leads";
$recordId = "440248774074";
$relatedListAPIName = "Notes";
GetRelatedRecords::initialize();
GetRelatedRecords::getRelatedRecords($moduleAPIName,$recordId,$relatedListAPIName);
