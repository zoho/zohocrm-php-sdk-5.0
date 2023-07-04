<?php
namespace record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\ConversionOptionsResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class LeadConversionOptions
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
    public static function leadConversionOptions($recordId)
    {
        $recordOperations = new RecordOperations();
        $moduleAPIName = "Leads";
        $response = $recordOperations->leadConversionOptions($recordId, $moduleAPIName);
        if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ConversionOptionsResponseWrapper) {
                $conversionOptionResponseWrapper = $responseHandler;
                $conversionOption = $conversionOptionResponseWrapper->getConversionoptions();
                $module = $conversionOption->getModulePreference();
                if ($module != null) {
                    echo ("ConversionOptions ModulePreference API-Name: " . $module->getAPIName() . "\n");
                    echo ("ConversionOptions ModulePreference ID: " . $module->getId());
                    echo ("\n");
                }
                $contacts = $conversionOption->getContacts();
                if ($contacts != null) {
                    foreach ($contacts as $contact) {
                        echo ("Record ID: " . $contact->getId());
                        echo ("\n");
                        echo ("Record KeyValues: ");
                        echo ("\n");
                        foreach ($contact->getKeyValues() as $keyName => $value) {
                            if ($value != null) {
                                if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                    echo ("Record KeyName : " . $keyName);
                                    $dataList = $value;
                                    foreach ($dataList as $data) {
                                        if (is_array($data)) {
                                            echo ("Record KeyName : " . $keyName . " - Value : ");
                                            foreach ($data as $mapValue) {
                                                echo ($mapValue->getKey() . " : " . $mapValue->getValue() . "\n");
                                            }
                                        } else {
                                            echo ($data);
                                            echo ("\n");
                                        }
                                    }
                                } else if (is_array($value)) {
                                    echo ("Record KeyName : " . $keyName . " - Value : ");
                                    foreach ($value as $key => $value) {
                                        echo ($key . " : " . $value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName . " - Value : " . $value);
                                    echo ("\n");
                                }
                            }
                        }
                    }
                }
                $preferenceFieldMatchedValue = $conversionOption->getPreferenceFieldMatchedValue();
                if ($preferenceFieldMatchedValue != null) {
                    $contactsPreferenceField = $preferenceFieldMatchedValue->getContacts();
                    if ($contactsPreferenceField != null) {
                        foreach ($contact as $contactsPreferenceField) {
                            echo ("Record ID: " . $contact->getId());
                            echo ("\n");
                            echo ("Record KeyValues: ");
                            echo ("\n");
                            foreach ($contact->getKeyValues() as $keyName => $value) {
                                if (is_array($value)) {
                                    echo ("Record KeyName : " . $keyName . " - Value : ");
                                    foreach ($value as $key => $value) {
                                        echo ($key . " : " . $value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName + " - Value : " . $value);
                                }
                            }
                        }
                    }
                    $accountsPreferenceField = $preferenceFieldMatchedValue->getAccounts();
                    if ($accountsPreferenceField != null) {
                        foreach ($accountsPreferenceField as $account) {
                            echo ("Record ID: " . $account->getId());
                            echo ("\n");
                            echo ("Record KeyValues: ");
                            echo ("\n");
                            foreach ($account->getKeyValues() as $keyName => $value) {
                                if (is_array($value)) {
                                    echo ("Record KeyName : " . $keyName . " - Value : ");
                                    foreach ($value as $key => $value) {
                                        echo ($key . " : " . $value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName . " - Value : " . $value);
                                }
                            }
                        }
                    }
                    $dealsPreferenceField = $preferenceFieldMatchedValue->getDeals();
                    if ($dealsPreferenceField != null) {
                        foreach ($dealsPreferenceField as $deal) {
                            echo ("Record ID: " . $deal->getId());
                            echo ("Record KeyValues: ");
                            echo ("\n");
                            foreach ($account->getKeyValues() as $keyName => $value) {
                                if (is_array($value)) {
                                    echo ("Record KeyName : " . $keyName . " - Value : ");
                                    foreach ($value as $key => $value) {
                                        echo ($key . " : " . $value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName . " - Value : " . $value);
                                }
                            }
                        }
                    }
                }
                $accounts = $conversionOption->getAccounts();
                if ($accounts != null) {
                    foreach ($accounts as $account) {
                        echo ("Record ID: " . $account->getId());
                        echo ("Record KeyValues: ");
                        echo ("\n");
                        foreach ($account->getKeyValues() as $keyName => $value) {
                            if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                echo ("Record KeyName : " . $keyName);
                                $dataList = $value;
                                foreach ($dataList as $data) {
                                    if (is_array($data)) {
                                        echo ("Record KeyName : " . $keyName . " - Value : ");
                                        foreach ($data as $mapValue) {
                                            echo ($mapValue->getKey() . " : " . $mapValue->getValue());
                                            echo ("\n");
                                        }
                                    } else {
                                        echo ($data);
                                        echo ("\n");
                                    }
                                }
                            } else if (is_array($value)) {
                                echo ("Record KeyName : " . $keyName . " - Value : ");
                                foreach ($value as $key => $value) {
                                    echo ($key . " : " . $value);
                                    echo ("\n");
                                }
                            } else {
                                echo ("Record KeyName : " . $keyName . " - Value : " . $value);
                                echo ("\n");
                            }
                        }
                    }
                }
                $deals = $conversionOption->getDeals();
                if ($deals != null) {
                    foreach ($deals as $deal) {
                        echo ("Record ID: " . $deal->getId());
                        echo ("Record KeyValues: ");
                        echo ("\n");
                        foreach ($contact->getKeyValues() as $keyName => $value) {
                            if ($value != null) {
                                if ((is_array($value) && sizeof($value) > 0) && isset($value[0])) {
                                    echo ("Record KeyName : " . $keyName);
                                    $dataList = $value;
                                    foreach ($dataList as $data) {
                                        if (is_array($data)) {
                                            echo ("Record KeyName : " . $keyName . " - Value : ");
                                            foreach ($data as $mapValue) {
                                                echo ($mapValue->getKey() . " : " . $mapValue->getValue() . "\n");
                                            }
                                        } else {
                                            echo ($data);
                                            echo ("\n");
                                        }
                                    }
                                } else if (is_array($value)) {
                                    echo ("Record KeyName : " . $keyName . " - Value : ");
                                    foreach ($value as $key => $value) {
                                        echo ($key . " : " . $value);
                                        echo ("\n");
                                    }
                                } else {
                                    echo ("Record KeyName : " . $keyName . " - Value : " . $value);
                                    echo ("\n");
                                }
                            }
                        }
                    }
                }
                $modulesWithMultipleLayouts = $conversionOption->getModulesWithMultipleLayouts();
                if ($modulesWithMultipleLayouts != null) {
                    foreach ($modulesWithMultipleLayouts as $module_1) {
                        echo ("ConversionOptions ModulesWithMultipleLayouts API-Name: " . $module_1->getAPIName());
                        echo ("ConversionOptions ModulesWithMultipleLayouts ID: " . $module_1->getId());
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
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
$recordId="347706112914002";
LeadConversionOptions::initialize();
LeadConversionOptions::leadConversionOptions($recordId);
