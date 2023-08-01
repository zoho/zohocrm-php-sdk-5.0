<?php
namespace customview;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\customviews\Criteria;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\customviews\APIException;
use com\zoho\crm\api\customviews\CustomViewsOperations;
use com\zoho\crm\api\customviews\BodyWrapper;
use com\zoho\crm\api\customviews\GetCustomViewsParam;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetCustomView
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

    public static function getCustomView(string $moduleAPIName, string $customViewId)
    {
        $customViewsOperations = new CustomViewsOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(GetCustomViewsParam::module(), $moduleAPIName);
        //Call getCustomView method that takes customViewId as parameter
        $response = $customViewsOperations->getCustomView($customViewId, $paramInstance);
        if ($response != null) {
            echo ("Status code : " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if ($responseHandler instanceof BodyWrapper) {
                $responseWrapper = $responseHandler;
                $customViews = $responseWrapper->getCustomViews();
                foreach ($customViews as $customView) {
                    echo ("CustomView DisplayValue: " . $customView->getDisplayValue() . "\n");
                    echo ("CustomView AccessType: " . $customView->getAccessType()->getValue() . "\n");
                    $criteria = $customView->getCriteria();
                    if ($criteria != null) {
                        self::printCriteria($criteria);
                    }
                    echo ("CustomView SystemName: " . $customView->getSystemName() . "\n");
                    echo ("CustomView SortBy: " . $customView->getSortBy() . "\n");
                    $createdBy = $customView->getCreatedBy();
                    if ($createdBy != null) {
                        echo ("CustomView Created By User-Name: " . $createdBy->getName() . "\n");
                        echo ("CustomView Created By User-ID: " . $createdBy->getId() . "\n");
                    }
                    $sharedToArray = $customView->getSharedTo();
                    if ($sharedToArray != null) {
                        foreach ($sharedToArray as $sharedTo) {
                            echo ("CustomView sharedTo Name: " . $sharedTo->getName() . "\n");
                            echo ("CustomView sharedTo Id: " . $sharedTo->getId() . "\n");
                            echo ("CustomView sharedTo Type: " . $sharedTo->getType() . "\n");
                            echo ("CustomView sharedTo Subordinates: " . $sharedTo->getSubordinates() . "\n");
                        }
                    }
                    echo ("CustomView Default: ");
                    print_r($customView->getDefault());
                    echo ("\n");
                    echo ("CustomView ModifiedTime: ");
                    print_r($customView->getModifiedTime());
                    echo ("\n");
                    echo ("CustomView Name: " . $customView->getName() . "\n");
                    echo ("CustomView SystemDefined: " . $customView->getSystemDefined() . "\n");
                    $modifiedBy = $customView->getModifiedBy();
                    if ($modifiedBy != null) {
                        echo ("CustomView Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo ("CustomView Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo ("CustomView ID: " . $customView->getId() . "\n");
                    $fields = $customView->getFields();
                    if ($fields != null) {
                        foreach ($fields as $field) {
                            echo ("CustomView Fields APIName: " . $field->getAPIName() . "\n");
                            echo ("CustomView Fields Id: " . $field->getId() . "\n");
                        }
                    }
                    echo ("CustomView Category: " . $customView->getCategory() . "\n");
                    echo ("CustomView LastAccessedTime: ");
                    print_r($customView->getLastAccessedTime());
                    echo ("\n");
                    echo ("CustomView SortOrder: ");
                    print_r($customView->getSortOrder());
                    echo ("\n");
                    if ($customView->getFavorite() != null) {
                        echo ("CustomView Favorite: " . $customView->getFavorite() . "\n");
                    }
                }
                $info = $responseWrapper->getInfo();
                if ($info != null) {
                    $translation = $info->getTranslation();
                    if ($translation != null) {
                        echo ("CustomView Info Translation PublicViews: " . $translation->getPublicViews() . "\n");
                        echo ("CustomView Info Translation OtherUsersViews: " . $translation->getOtherUsersViews() . "\n");
                        echo ("CustomView Info Translation SharedWithMe: " .  $translation->getSharedWithMe() . "\n");
                        echo ("CustomView Info Translation CreatedByMe: " .  $translation->getCreatedByMe() . "\n");
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
    private static function printCriteria(Criteria $criteria)
	{
		if ($criteria->getComparator() != null)
		{
			echo("CustomView Criteria Comparator: " . $criteria->getComparator() . "\n");
		}
        if ($criteria->getField() != null)
        {
            echo("CustomView Criteria field name: " . $criteria->getField()->getAPIName() . "\n");
        }
        if ($criteria->getValue() != null)
        {
            echo("CustomView Criteria Value: " . $criteria->getValue() . "\n");
        }
        $criteriaGroup = $criteria->getGroup();
        if ($criteriaGroup != null)
        {
            foreach ($criteriaGroup as $criteria1)
			{
                GetCustomView::printCriteria($criteria1);
            }
		}
		if ($criteria->getGroupOperator() != null)
        {
            echo("CustomView Criteria Group Operator: " . $criteria->getGroupOperator() . "\n");
        }
	}
}
$moduleAPIName = "Leads";
$customViewId = "440248892044";
GetCustomView::initialize();
GetCustomView::getCustomView($moduleAPIName,$customViewId);
