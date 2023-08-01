<?php
namespace resheduledhistory;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\reschedulehistory\APIException;
use com\zoho\crm\api\reschedulehistory\RescheduleHistoryOperations;
use com\zoho\crm\api\reschedulehistory\ResponseWrapper;
use com\zoho\crm\api\reschedulehistory\GetAppointmentsRescheduledHistoryParam;
use com\zoho\crm\api\util\Choice;

include_once "vendor/autoload.php";

class GetAppointmentsRescheduledHistory
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

    public static function getAppointmentsRescheduledHistory()
    {
        $rescheduleHistoryOperations = new RescheduleHistoryOperations();
        $paraminstnance = new ParameterMap();
        $paraminstnance->add(GetAppointmentsRescheduledHistoryParam::fields(), "Rescheduled_To");
        $response = $rescheduleHistoryOperations->getAppointmentsRescheduledHistory($paraminstnance);
        if ($response != null) {
            echo("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrapper = $responseHandler;
                    $data = $responseWrapper->getData();
                    if ($data != null)
                    {
                        foreach ($data as $history) {
                            echo("CurrencySymbol: " . $history->getCurrencySymbol());
                            echo("reviewProcess: " . $history->getReviewProcess());
                            echo("rescheduleReason: " . $history->getRescheduleReason());
                            echo("sharingPermission: " . $history->getSharingPermission());
                            echo("Name: " . $history->getName());
                            echo("Review: " . $history->getReview());
                            echo("State: " . $history->getState());
                            echo("canvasId: " . $history->getCanvasId());
                            echo("processFlow: " . $history->getProcessFlow());
                            echo("Id: " . $history->getId());
                            echo("ziaVisions: " . $history->getZiaVisions());
                            echo("approved: " . $history->getApproved());
                            echo("ziaVisions: " . $history->getZiaVisions());
                            echo("editable: " . $history->getEditable());
                            echo("orchestration: " . $history->getOrchestration());
                            echo("inMerge: " . $history->getInMerge());
                            echo("approvalState: " . $history->getApprovalState());
                            echo("rescheduleNote: " . $history->getRescheduleNote());
                            echo("rescheduledTo: " . $history->getRescheduledTo());
                            echo("rescheduledTime: " . $history->getRescheduledTime());
                            echo("rescheduledFrom: " . $history->getRescheduledFrom());
                            $appointmentName = $history->getAppointmentName();
                            if ($appointmentName != null) {
                                echo("Appointment Name : " . $appointmentName->getName());
                                echo("Appointment Id : " . $appointmentName->getId());
                            }
                            $apporoval = $history->getApproval();
                            if ($apporoval != null) {
                                echo("delegate : " . $apporoval->getDelegate());
                                echo("approve : " . $apporoval->getApprove());
                                echo("reject : " . $apporoval->getReject());
                                echo("resubmit : " . $apporoval->getResubmit());
                            }
                            $modifiedBy = $history->getModifiedBy();
                            if ($modifiedBy != null) {
                                echo("modifiedBy ID: " . $modifiedBy->getId());
                                echo("modifiedBy NAme: " . $modifiedBy->getName());
                                echo("modifiedBy Email: " . $modifiedBy->getEmail());
                            }
                            $resheduledBy = $history->getRescheduledBy();
                            if ($resheduledBy != null) {
                                echo("rescheduled BY : " . $resheduledBy->getId());
                                echo("rescheduled BY : " . $resheduledBy->getName());
                                echo("rescheduled BY : " . $resheduledBy->getEmail());
                            }
                            $createdBy = $history->getCreatedBy();
                            if ($createdBy != null) {
                                echo("created BY : " . $createdBy->getId());
                                echo("created BY : " . $createdBy->getName());
                                echo("created BY : " . $createdBy->getEmail());
                            }
                        }
                    }
                    $info = $responseWrapper->getInfo();
                    foreach ($info as $info1)
                    {
                        if ($info1 != null) {
                            if ($info1->getPerPage() != null) {
                                echo("RelatedRecord Info Perpage : " . $info1->getPerPage());
                            }
                            if ($info1->getCount() != null) {
                                echo("RelatedRecord Info Perpage : " . $info1->getCount());
                            }
                            if ($info1->getPage() != null) {
                                echo("RelatedRecord Info Perpage : " . $info1->getPage());
                            }
                            if ($info1->getMoreRecords() != null) {
                                echo("RelatedRecord Info Perpage : " . $info1->getMoreRecords());
                            }
                        }
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
            }
        }
    }
}
GetAppointmentsRescheduledHistory::initialize();
GetAppointmentsRescheduledHistory::getAppointmentsRescheduledHistory();