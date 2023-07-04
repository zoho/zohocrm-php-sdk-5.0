<?php
namespace bulkread;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\bulkread\BulkReadOperations;
use com\zoho\crm\api\bulkread\CallBack;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\bulkread\Query;
use com\zoho\crm\api\bulkread\Criteria;
use com\zoho\crm\api\bulkread\ActionWrapper;
use com\zoho\crm\api\bulkread\SuccessResponse;
use com\zoho\crm\api\bulkread\APIException;
use com\zoho\crm\api\bulkread\RequestWrapper;
use com\zoho\crm\api\modules\MinifiedModule;
use com\zoho\crm\api\fields\MinifiedField;

require_once "vendor/autoload.php";

class CreateBulkReadJob
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
     * <h3> Create BulkRead Job </h3>
     * This method is used to create a Bulkread job to export records.
     * @param moduleAPIName The API Name of the record's module
     * @throws Exception
     */
    public static function createBulkReadJob(string $moduleAPIName)
    {
        $bulkReadOperations = new BulkReadOperations();
        $requestWrapper = new RequestWrapper();
        $callback = new CallBack();
        $callback->setUrl("https://www.example.com/callback");
        $callback->setMethod(new Choice("post"));
        //The Bulkread Job's details is posted to this URL on successful completion / failure of job.
        $requestWrapper->setCallback($callback);
        $query = new Query();
        $module = new MinifiedModule();
        $module->setAPIName($moduleAPIName);
        //Specifies the API Name of the module to be read.
        $query->setModule($module);
        //Specifies the unique ID of the custom view whose records you want to export.
        // $query->setCvid("34770610087501");
        // List of Field API Names
        $fieldAPINames = array();
        array_push($fieldAPINames, "Last_Name");
        //Specifies the API Name of the fields to be fetched.
        $query->setFields($fieldAPINames);
        $query->setPage(1);
        $criteria = new Criteria();
        $criteria->setGroupOperator(new Choice("or"));
        $criteriaList = array();
        $group11 = new Criteria();
        $group11->setGroupOperator(new Choice("and"));
        $groupList11 = array();
        $group111 = new Criteria();
        $field = new MinifiedField();
        $field->setAPIName("Last_Name");
        $group111->setField($field);
        $group111->setComparator(new Choice("equal"));
        $group111->setValue("TestPHPSDK");
        array_push($groupList11, $group111);
        $group112 = new Criteria();
        $field = new MinifiedField();
        $field->setAPIName("Owner");
        $group112->setField($field);
        $group112->setComparator(new Choice("in"));
        $owner = array("3477061173021");
        $group112->setValue($owner);
        array_push($groupList11, $group112);
        $group11->setGroup($groupList11);
        array_push($criteriaList, $group11);
        $group12 = new Criteria();
        $group12->setGroupOperator(new Choice("or"));
        $groupList12 = array();
        $group121 = new Criteria();
        $field = new MinifiedField();
        $field->setAPIName("Company");
        $group121->setField($field);
        $group121->setComparator(new Choice("equal"));
        $group121->setValue("KK");
        array_push($groupList12, $group121);
        $group122 = new Criteria();
        $field = new MinifiedField();
        $field->setAPIName("Created_Time");
        // To set API name of a field.
        $group122->setField($field);
        // To set comparator(eg: equal, greater_than.).
        $group122->setComparator(new Choice("between"));
        $createdTime = array(date_create("2020-07-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())), date_create("2020-10-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
        // To set the value to be compare.
        $group122->setValue($createdTime);
        array_push($groupList12, $group122);
        $group12->setGroup($groupList12);
        array_push($criteriaList, $group12);
        $criteria->setGroup($criteriaList);
        //To filter the records to be exported.
        $query->setCriteria($criteria);
        $requestWrapper->setQuery($query);
        //Specify the value for this key as "ics" to export all records in the Events module as an ICS file.
        // $requestWrapper->setFileType(new Choice("ics"));
        //Call createBulkReadJob method that takes RequestWrapper instance as parameter
        $response = $bulkReadOperations->createBulkReadJob($requestWrapper);
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
                            echo ($key . " : ");
                            print_r($value);
                            echo ("\n");
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
                    echo ($key . " : ");
                    print_r($value);
                    echo ("\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
}
$moduleAPIName="leads";
CreateBulkReadJob::initialize();
CreateBulkReadJob::createBulkReadJob($moduleAPIName);
