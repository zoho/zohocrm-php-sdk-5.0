<?php
namespace dealcontactroles;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\dealcontactroles\APIException;
use com\zoho\crm\api\dealcontactroles\BodyWrapper;
use com\zoho\crm\api\dealcontactroles\ContactRole;
use com\zoho\crm\api\dealcontactroles\DealContactRolesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetContactRoleOfDeal
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
    public static function getContactRoleOfDeal($contactId, $dealId)
    {
        $contactRolesOperations = new DealContactRolesOperations();
        //Call getContactRoleOfDeal method that takes Param instance as parameter
        $response = $contactRolesOperations->getAssociatedContactRolesSpecificToContact($contactId, $dealId);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof BodyWrapper) {
                    $responseWrapper = $responseHandler;
                    $records = $responseWrapper->getData();
                    foreach ($records as $record) {
                        echo ("Record ID: " . $record->getId() . "\n");
                        $createdBy = $record->getCreatedBy();
                        if ($createdBy != null) {
                            echo ("Record Created By User-ID: " . $createdBy->getId() . "\n");
                            echo ("Record Created By User-Name: " . $createdBy->getName() . "\n");
                            echo ("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
                        }
                        echo ("Record CreatedTime: ");
                        print_r($record->getCreatedTime());
                        echo ("\n");
                        $modifiedBy = $record->getModifiedBy();
                        if ($modifiedBy != null) {
                            echo ("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");
                            echo ("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");
                            echo ("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                        }
                        echo ("Record CreatedTime: ");
                        print_r($record->getModifiedTime());
                        echo ("\n");
                        //To get particular field value
                        echo ("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n"); // FieldApiName
                        echo ("Record KeyValues: \n");
                        foreach ($record->getKeyValues() as $keyName => $value) {
                            if ($value != null) {
                                if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                    echo ("Record KeyName : " . $keyName . "\n");
                                    $dataList = $value;
                                    foreach ($dataList as $data) {
                                        if (is_array($data)) {
                                            echo ("Record KeyName : " . $keyName  . " - Value :  \n");
                                            foreach ($data as $key => $arrayValue) {
                                                echo ($key . " : " . $arrayValue);
                                            }
                                        } else if ($value instanceof ContactRole) {
                                            echo ("Record ContactRole Name : " . $value->getName() . "\n");
                                            echo ("Record ContactRole Id : " . $value->getId() . "\n");
                                        } else {
                                            print_r($data);
                                            echo ("\n");
                                        }
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName  . " - Value : ");
                                    print_r($value);
                                    echo ("\n");
                                }
                            }
                        }
                    }
                    $info = $responseWrapper->getInfo();
                    if ($info != null) {
                        if ($info->getCount() != null) {
                            echo ("Record Info Count: " . $info->getCount() . "\n");
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
                    echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
                }
            } else { //If response is not as expected
                print_r($response);
            }
        }
    }
}
$contactId="440248001330018";
$dealId="440248001324032";
GetContactRoleOfDeal::initialize();
GetContactRoleOfDeal::getContactRoleOfDeal($contactId,$dealId);
