<?php
namespace fields;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\fields\APIException;
use com\zoho\crm\api\fields\FieldsOperations;
use com\zoho\crm\api\fields\GetFieldsParam;
use com\zoho\crm\api\fields\BodyWrapper;
use com\zoho\crm\api\fields\Unique;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetFields
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
     * <h3> Get Fields </h3>
     * This method is used to get metadata about all the fields of a module and print the response.
     * @throws Exception
     * @param moduleAPIName The API Name of the module to get fields
     */
    public static function getFields(string $moduleAPIName)
    {
        //example, moduleAPIName = "module_api_name";
        $fieldOperations = new FieldsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetFieldsParam::module(), $moduleAPIName);
        // $paramInstance->add(GetFieldsParam::type(), "unused");
        //Call getFields method that takes paramInstance as parameter
        $response = $fieldOperations->getFields($paramInstance);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $fields = $responseWrapper->getFields();
                if ($fields != null) {
                    foreach ($fields as $field) {
                        echo ("Field SystemMandatory: ");
                        print_r($field->getSystemMandatory());
                        echo ("\n");
                        echo ("Field Webhook: ");
                        print_r($field->getWebhook());
                        print_r("\n");
                        echo ("Field JsonType: " . $field->getJsonType() . "\n");
                        $privateInfo = $field->getPrivate();
                        if ($privateInfo != null) {
                            echo ("Private Details\n");
                            echo ("Field Private Type: " . $privateInfo->getType() . "\n");
                            echo ("Field Private Export: " . $privateInfo->getExport() . "\n");
                            echo ("Field Private Restricted: " . $privateInfo->getRestricted() . "\n");
                        }
                        $crypt = $field->getCrypt();
                        if ($crypt != null) {
                            echo ("Field Crypt Mode: " . $crypt->getMode() . "\n");
                            echo ("Field Crypt Column: " . $crypt->getColumn() . "\n");
                            echo ("Field Crypt Table: " . $crypt->getTable() . "\n");
                            echo ("Field Crypt Status: " . $crypt->getStatus() . "\n");
                            $encFldIds = $crypt->getEncfldids();
                            if ($encFldIds != null) {
                                echo ("EncFldIds : ");
                                foreach ($encFldIds as $encFldId) {
                                    echo ($encFldId . "\n");
                                }
                            }
                            echo ("Field Crypt Notify: " . $crypt->getNotify() . "\n");
                        }
                        echo ("Field FieldLabel: " . $field->getFieldLabel() . "\n");
                        $tooltip = $field->getTooltip();
                        if ($tooltip != null) {
                            echo ("Field ToolTip Name: " . $tooltip->getName()->getValue() . "\n");
                            echo ("Field ToolTip Value: " . $tooltip->getValue() . "\n");
                        }
                        echo ("Field CreatedSource: " . $field->getCreatedSource() . "\n");
                        echo ("Field Type: " . $field->getType() . "\n");
                        echo ("Field FieldReadOnly: ");
                        print_r($field->getFieldReadOnly());
                        echo ("\n");
                        echo ("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");
                        echo ("Field DisplayType: " . $field->getDisplayType() . "\n");
                        echo ("Field UIType: " . $field->getUiType() . "\n");
                        echo ("Field ReadOnly: ");
                        print_r($field->getReadOnly());
                        echo ("\n");
                        $associationDetails = $field->getAssociationDetails();
                        if ($associationDetails != null) {
                            $lookupField = $associationDetails->getLookupField();
                            if ($lookupField != null) {
                                echo ("Field AssociationDetails LookupField ID: " . $lookupField->getId() . "\n");
                                echo ("Field AssociationDetails LookupField Name: " . $lookupField->getName() . "\n");
                            }
                            $relatedField = $associationDetails->getRelatedField();
                            if ($relatedField != null) {
                                echo ("Field AssociationDetails RelatedField ID: " . $relatedField->getId() . "\n");
                                echo ("Field AssociationDetails RelatedField Name: " . $relatedField->getName() . "\n");
                            }
                        }
                        if ($field->getQuickSequenceNumber() != null) {
                            echo ("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }
                        echo ("Field BusinesscardSupported: ");
                        print_r($field->getBusinesscardSupported());
                        echo ("\n");
                        $multiModuleLookup = $field->getMultiModuleLookup();
                        if ($multiModuleLookup != null) {
                            echo ("Field MultiModuleLookup APIName: " . $multiModuleLookup->getAPIName());
                            echo ("\n");
                            echo ("Field MultiModuleLookup DisplayLabel: " . $multiModuleLookup->getDisplayLabel());
                            echo ("\n");
                            $modules = $multiModuleLookup->getModules();
                            if ($modules != null) {
                                foreach ($modules as $module) {
                                    echo ("Field MultiModuleLookup Module ID: " . $module->getId());
                                    echo ("\n");
                                    echo ("Field MultiModuleLookup Module APIName: " . $module->getAPIName());
                                    echo ("\n");
                                }
                            }
                        }
                        $currency = $field->getCurrency();
                        if ($currency != null) {
                            echo ("Field Currency RoundingOption: " . $currency->getRoundingOption()->getValue() . "\n");
                            if ($currency->getPrecision() != null) {
                                echo ("Field Currency Precision: " . $currency->getPrecision() . "\n");
                            }
                        }
                        echo ("Field ID: " . $field->getId() . "\n");
                        echo ("Field CustomField: ");
                        print_r($field->getCustomField());
                        echo ("\n");
                        $lookup = $field->getLookup();
                        if ($lookup != null) {
                            echo ("Field Lookup DisplayLabel: " . $lookup->getDisplayLabel() . "\n");
                            echo ("Field Lookup RevalidateFilterDuringEdit: " . $lookup->getRevalidateFilterDuringEdit() . "\n");
                            echo ("Field Lookup APIName: " . $lookup->getAPIName() . "\n");
                            $module = $lookup->getModule();
                            if ($module != null) {
                                echo ("Field Lookup Module ID: " . $module->getId() . "\n");
                                echo ("Field Lookup Module Name: " . $module->getAPIName() . "\n");
                            }
                            if ($lookup->getId() != null) {
                                echo ("Field Lookup ID: " . $lookup->getId() . "\n");
                            }
                            $querydetails = $lookup->getQueryDetails();
                            if ($querydetails != null) {
                                echo ("Field ModuleLookup QueryDetails Query Id: " . $querydetails->getQueryId());
                            }
                        }
                        echo ("Field Filterable: " . $field->getFilterable() . "\n");
                        echo ("Field Visible: ");
                        print_r($field->getVisible());
                        echo ("\n");
                        $profiles = $field->getProfiles();
                        if ($profiles != null) {
                            foreach ($profiles as $profile) {
                                echo ("Field Profile PermissionType: " . $profile->getPermissionType() . "\n");
                                echo ("Field Profile Name: " . $profile->getName() . "\n");
                                echo ("Field Profile Id: " . $profile->getId() . "\n");
                            }
                        }
                        if ($field->getLength() != null) {
                            echo ("Field Length: " . $field->getLength()->getValue() . "\n");
                        }
                        $viewType = $field->getViewType();
                        if ($viewType != null) {
                            echo ("Field ViewType View: " . $viewType->getView() . "\n");
                            echo ("Field ViewType Edit: " . $viewType->getEdit() . "\n");
                            echo ("Field ViewType Create: " . $viewType->getCreate() . "\n");
                            echo ("Field ViewType QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }
                        if ($field->getDisplayField() != null) {
                            // check if field is DisplayField
                            echo ("Field DisplayField " . $field->getDisplayField());
                        }
                        echo ("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");
                        echo ("Field Sortable: ");
                        print_r($field->getSortable());
                        echo ("\n");
                        $subform = $field->getAssociatedModule();
                        if ($subform != null) {
                            echo ("Field Subform Module: " . $subform->getModule() . "\n");
                            if ($subform->getId() != null) {
                                echo ("Field Subform ID: " . $subform->getId() . "\n");
                            }
                        }
                        if ($field->getSequenceNumber() != null) {
                            // get UI type of field
                            echo ("Field sequence number " . $field->getSequenceNumber());
                        }
                        $external = $field->getExternal();
                        if ($external != null) {
                            echo ("Field External Show: " . $external->getShow() . "\n");
                            echo ("Field External Type: " . $external->getType()->getValue() . "\n");
                            echo ("Field External AllowMultipleConfig: " . $external->getAllowMultipleConfig() . "\n");
                        }
                        echo ("Field APIName: " . $field->getAPIName() . "\n");
                        $unique = $field->getUnique();
                        if ($unique != null) {
                            if ($unique instanceof Unique) {
                                echo ("Field Unique Casesensitive : " . $unique->getCasesensitive() . "\n");
                            } else {
                                echo ("Field Unique : ");
                                print_r($unique);
                                echo ("\n");
                            }
                        }
                        if ($field->getHistoryTracking() != null) {
                            echo ("Field HistoryTracking: " . print_r($field->getHistoryTracking()) . "\n");
                            $historytracking = $field->getHistoryTracking();
                            $module =  $historytracking->getModule();
                            if ($module != null) {
                                $moduleLayout = $module->getLayout();
                                if ($moduleLayout != null) {
                                    echo ("Module layout id" . $moduleLayout->getId());
                                }
                                echo ("Module layout display label" . $module->getDisplayLabel());
                                echo ("Module layout api name" . $module->getAPIName());
                                echo ("Module layout module" . $module->getId());
                                echo ("Module layout id" . $module->getModule());
                                echo ("Module layout module name" . $module->getModuleName());
                            }
                            $durationConfigured = $historytracking->getDurationConfiguredField();
                            if ($durationConfigured != null) {
                                echo ("historytracking duration configured field" . $durationConfigured->getId());
                            }
                        }
                        echo ("Field DataType: " . $field->getDataType()->getValue() . "\n");
                        $formula = $field->getFormula();
                        if ($formula != null) {
                            echo ("Field Formula ReturnType : " . $formula->getReturnType() . "\n");
                            if ($formula->getExpression() != null) {
                                echo ("Field Formula Expression : " . $formula->getExpression() . "\n");
                            }
                        }
                        if ($field->getDecimalPlace() != null) {
                            echo ("Field DecimalPlace: " . $field->getDecimalPlace() . "\n");
                        }
                        echo ("Field MassUpdate: " . $field->getMassUpdate() . "\n");
                        if ($field->getBlueprintSupported() != null) {
                            echo ("Field BlueprintSupported: " . $field->getBlueprintSupported() . "\n");
                        }
                        $multiSelectLookup = $field->getMultiselectlookup();
                        if ($multiSelectLookup != null) {
                            echo ("Field MultiSelectLookup DisplayLabel: " . $multiSelectLookup->getDisplayLabel() . "\n");
                            $module = $multiSelectLookup->getLinkingModule();
                            if ($module != null) {
                                echo ("Field MultiSelectLookup Module ID: " . $module->getId() . "\n");
                                echo ("Field MultiSelectLookup Module Name: " . $module->getAPIName() . "\n");
                            }
                            echo ("Field MultiSelectLookup LookupApiname: " . $multiSelectLookup->getLookupApiname() . "\n");
                            echo ("Field MultiSelectLookup APIName: " . $multiSelectLookup->getAPIName() . "\n");
                            $connectedModule = $multiSelectLookup->getConnectedModule();
                            if ($connectedModule != null) {
                                echo ("Field MultiSelectLookup ConnectedModule ID: " . $connectedModule->getId() . "\n");
                                echo ("Field MultiSelectLookup ConnectedModule Name: " . $connectedModule->getAPIName() . "\n");
                            }
                            echo ("Field MultiSelectLookup ConnectedlookupApiname: " . $multiSelectLookup->getConnectedlookupApiname() . "\n");
                            echo ("Field MultiSelectLookup ID: " . $multiSelectLookup->getId() . "\n");
                        }
                        $pickListValues = $field->getPickListValues();
                        if ($pickListValues != null) {
                            foreach ($pickListValues as $pickListValue1) {
                                self::printPickListValue($pickListValue1);
                            }
                        }
                        $autoNumber = $field->getAutoNumber();
                        if ($autoNumber != null) {
                            echo ("Field AutoNumber Prefix: " . $autoNumber->getPrefix() . "\n");
                            echo ("Field AutoNumber Suffix: " . $autoNumber->getSuffix() . "\n");
                            if ($autoNumber->getStartNumber() != null) {
                                echo ("Field AutoNumber StartNumber: " . $autoNumber->getStartNumber() . "\n");
                            }
                        }
                        if ($field->getDefaultValue() != null) {
                            echo ("Field DefaultValue: " . $field->getDefaultValue() . "\n");
                        }
                        if ($field->getConvertMapping() != null) {
                            $convertMapping = $field->getConvertMapping();
                            print_r($convertMapping->getAccounts());
                            echo ("\n");
                            print_r($convertMapping->getContacts());
                            echo ("\n");
                            print_r($convertMapping->getDeals());
                            echo ("\n");
                        }
                        // get multi user lookup for field
                        if ($field->getMultiuserlookup() != null) {
                            $multiuserlookup = $field->getMultiuserlookup();
                            echo ("Field Multiuserlookup DisplayLabel: " . $multiuserlookup->getDisplayLabel() . "\n");
                            $module = $multiuserlookup->getLinkingModule();
                            if ($module != null) {
                                echo ("Field Multiuserlookup Module ID: " . $module->getId() . "\n");
                                echo ("Field Multiuserlookup Module Name: " . $module->getAPIName() . "\n");
                            }
                            echo ("Field Multiuserlookup LookupApiname: " . $multiuserlookup->getLookupApiname() . "\n");
                            echo ("Field Multiuserlookup APIName: " . $multiuserlookup->getAPIName() . "\n");
                            $connectedModule = $multiuserlookup->getConnectedModule();
                            if ($connectedModule != null) {
                                echo ("Field Multiuserlookup ConnectedModule ID: " . $connectedModule->getId() . "\n");
                                echo ("Field Multiuserlookup ConnectedModule Name: " . $connectedModule->getAPIName() . "\n");
                            }
                            echo ("Field Multiuserlookup ConnectedlookupApiname: " . $multiuserlookup->getConnectedlookupApiname() . "\n");
                            echo ("Field Multiuserlookup ID: " . $multiuserlookup->getId() . "\n");
                        }
                    }
                }
            }
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . ": " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
        }
    }
    private static function printPickListValue($pickListValue)
    {
        echo ("Field PickListValue DisplayValue: " . $pickListValue->getDisplayValue() . "\n");
        echo (" Fields PickListValue Probability: " . $pickListValue->getProbability());
        $forecastCategory = $pickListValue->getForecastCategory();
        if ($forecastCategory != null) {
            echo ("Field PickListValue ForecastCategory ID: " . $forecastCategory->getId() . "\n");
            echo ("Field PickListValue ForecastCategory Name: " . $forecastCategory->getName() . "\n");
        }
        if ($pickListValue->getSequenceNumber() != null) {
            echo (" Field PickListValue SequenceNumber: " . $pickListValue->getSequenceNumber() . "\n");
        }
        echo ("Field PickListValue ExpectedDataType: " . $pickListValue->getExpectedDataType() . "\n");
        echo (" Fields PickListValue ForecastType: " . $pickListValue->getForecastType());
        if ($pickListValue->getMaps() != null) {
            foreach ($pickListValue->getMaps() as $map) {
                echo ("Field PickListValue Maps APIName: " . $map->getAPIName() . "\n");
                $pickListValues = $map->getPickListValues();
                if ($pickListValues != null) {
                    foreach ($pickListValues as $pickListValue1) {
                        self::printPickListValue($pickListValue1);
                    }
                }
            }
        }
        echo ("Field PickListValue ActualValue: " . $pickListValue->getActualValue() . "\n");
        echo ("Field PickListValue SysRefName: " . $pickListValue->getSysRefName() . "\n");
        if ($pickListValue->getType() != null) {
            echo ("Field PickListValue Type: " . $pickListValue->getType()->getValue() . "\n");
        }
        echo ("Field PickListValue Id: " . $pickListValue->getId() . "\n");
    }
}
$moduleAPIName="leads";
GetFields::initialize();
GetFields::getFields($moduleAPIName);
