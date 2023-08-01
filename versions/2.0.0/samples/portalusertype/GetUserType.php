<?php
namespace portalusertype;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\portalusertype\APIException;
use com\zoho\crm\api\portalusertype\PortalUserTypeOperations;
use com\zoho\crm\api\portalusertype\ResponseWrapper;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetUserType
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
    public static function getUserType(String $portalName, String $usertypeID)
    {
        $userTypeOperations = new PortalUserTypeOperations($portalName);
        $response = $userTypeOperations->getUserType($usertypeID);
        if ($response != null) {
            echo("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $userType = $responseWrapper->getUserType();
                if ($userType != null)
                {
                    foreach ($userType as $userType1)
                    {
                        echo("UserType CreatedTime: ");
                        print_r($userType1->getCreatedTime());
                        echo("Usertype Default: " . $userType1->getDefault()). "\n";
                        echo("userType ModofiedTime : ");
                        print_r($userType1->getModifiedTime());
                        $personalityModule = $userType1->getPersonalityModule();
                        if ($personalityModule != null)
                        {
                            echo("UserType PersonalityModule ID: " . $personalityModule->getId(). "\n");
                            echo("UserType PersonalityModule APIName: " . $personalityModule->getAPIName(). "\n");

                            echo("UserType PersonalityModule PluralLabel: " . $personalityModule->getPluralLabel(). "\n");
                        }

                        echo("UserType Name: " . $userType1->getName() . "\n");

                        $modifiedBy = $userType1->getModifiedBy();
                        if ($modifiedBy != null)
                        {
                            echo("UserType ModifiedBy User-ID: " . $modifiedBy->getId() . "\n");
                            echo("UserType ModifiedBy User-Name: " . $modifiedBy->getName() . "\n");
                        }

                        echo("UserType Active: " . $userType1->getActive() . "\n");

                        echo("UserType Id: " .$userType1->getId() . "\n");

                        $createdBy = $userType1->getCreatedBy();
                        if ($createdBy != null)
                        {
                            echo("UserType CreatedBy User-ID: " . $createdBy->getId() . "\n");
                            echo("UserType CreatedBy User-Name: " . $createdBy->getName() . "\n");
                        }

                        echo("UserType NoOfUsers: " . $userType1->getNoOfUsers() . "\n");
                        $modules = $userType1->getModules();
                        if ($modules != null) {
                            foreach ($modules as $module)
                            {
                                echo("UserType Modules PluralLabel: " . $module->getPluralLabel());
                                echo("UserType Modules SharedType: " . $module->getSharedType()->getValue());

                                echo("UserType Modules APIName: " . $module->getAPIName());

                                $permissions = $module->getPermissions();

								if ($permissions != null)
                                {
                                    echo("UserType Modules Permissions View: " . $permissions->getView()->getValue(). "\n");

                                    echo("UserType Modules Permissions Edit: " . $permissions->getEdit(). "\n");

                                    echo("UserType Modules Permissions EditSharedRecords: " . $permissions->getEditSharedRecords() . "\n");

                                    echo("UserType Modules Permissions Create: "  . $permissions->getCreate() . "\n");

                                    echo("UserType Modules Permissions Delete: " . $permissions->getDelete(). "\n");
                                }

                                echo("UserType Modules Id: " . $module->getId() . "\n");

								$filters = $module->getFilters();

								if ($filters != null)
                                {
                                    forEach($filters as $filter) {

                                        echo("UserType Modules Filters APIName: " . $filter->getAPIName() . '\n');

										echo("UserType Modules Filters DisplayLabel: " . $filter->getDisplayLabel() . "\n");

										echo("UserType Modules Filters Id: " . $filter->getId() . "\n");
									}
								}

								$fields = $module->getFields();

								if ($fields != null)
                                {
                                   forEach($fields as $field) {
                                       echo("UserType Modules Fields APIName: " . $field->getAPIName() . "\n");

                                       echo("UserType Modules Fields ReadOnly: " . $field->getReadOnly() . "\n");

                                       echo("UserType Modules Fields Id: " . $field->getId() . "\n");
                                   }
								}

								$layouts = $module->getLayouts();

								if ($layouts != null)
                                {
                                    forEach($layouts as $layout )
                                {
                                        echo("UserType Modules Layouts Name: " . $layout->getName() . "\n");

										echo("UserType Modules Layouts DisplayLabel: " . $layout->getDisplayLabel() . "\n");

										echo("UserType Modules Layouts Id: " . $layout->getId() . "\n");
									}
								}

								$views = $module->getViews();

								if ($views != null)
                                {
                                    echo("UserType Modules Views DisplayLabel: " . $views->getDisplayLabel() . "\n");

                                    echo("UserType Modules Views Name: " . $views->getName() . "\n");

                                    echo("UserType Modules Views Id: " . $views->getId() . "\n");

                                    echo("UserType Modules Permissions Type: " . $views->getType() . "\n");
                                }
                            }
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
}
$portalName = "PortalsAPITest101";
$userTypeId = "440248001304019";
GetUserType::initialize();
GetUserType::getUserType($portalName, $userTypeId);