<?php
namespace record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\ConvertBodyWrapper;
use com\zoho\crm\api\record\LeadConverter;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\{Record, Deals};
use com\zoho\crm\api\record\CarryOverTags;
use com\zoho\crm\api\users\MinifiedUser;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class ConvertLead
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

    public static function convertLead(string $recordId)
    {
        $recordOperations = new RecordOperations();
        $request = new ConvertBodyWrapper();
        $data = array();
        $record1 = new LeadConverter();
        $record1->setOverwrite(true);
        $record1->setNotifyLeadOwner(true);
        $record1->setNotifyNewEntityOwner(true);
        $accounts = new Record();
        $accounts->setId("347706112263002");
        $record1->setAccounts($accounts);
        $contacts = new Record();
        $contacts->setId("347706112263005");
        $record1->setContacts($contacts);
        $assignTo = new MinifiedUser();
        $assignTo->setId("3477061173021");
        $record1->setAssignTo($assignTo);
        $deals = new Record();
        /*
         * Call addFieldValue method that takes two arguments
         * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
         * 2 -> Value
         */
        $deals->addFieldValue(Deals::DealName(), "deal_name");
        $deals->addFieldValue(Deals::Description(), "deals description");
        $deals->addFieldValue(Deals::ClosingDate(), new \DateTime("2021-06-02"));
        $deals->addFieldValue(Deals::Stage(), new Choice("Closed Won"));
        $deals->addFieldValue(Deals::Amount(), 50.7);
        $deals->addKeyValue("Pipeline", new Choice("Qualification"));
        /*
         * Call addKeyValue method that takes two arguments
         * 1 -> A string that is the Field's API Name
         * 2 -> Value
         */
        $deals->addKeyValue("Custom_field", "Value");
        $deals->addKeyValue("Custom_field_2", "value");
        $record1->setDeals($deals);
        $carryOverTags = new CarryOverTags();
        $carryOverTags->setAccounts(["Test"]);
        $carryOverTags->setContacts(["Test"]);
        $carryOverTags->setDeals(["Test"]);
        $record1->setCarryOverTags($carryOverTags);
        array_push($data, $record1);
        $request->setData($data);
        $response = $recordOperations->convertLead($recordId, $request);
        if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
            if ($response->isExpected()) {
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
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo ($key . " : ");
                                print_r($value);
                                echo ("\n");
                            }
                            echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
                        } else if ($actionResponse instanceof APIException) {
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
                } else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo ("Code: " . $exception->getCode()->getValue() . "\n");
                    echo ("Details: ");
                    foreach ($exception->getDetails() as $key => $value) {
                        echo ($key . " : " . $value . "\n");
                    }
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            } else {
                print_r($response);
            }
        }
    }
}
$recordId = "347706114461016";
ConvertLead::initialize();
ConvertLead::convertLead($recordId);
