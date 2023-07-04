<?php 
namespace com\zoho\crm\api\usertypeusers;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class UserTypeUsersOperations
{

	/**
	 * The method to get users of user type
	 * @param string $userTypeId A string
	 * @param string $portalName A string
	 * @param ParameterMap $paramInstance An instance of ParameterMap
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getUsersOfUserType(string $userTypeId, string $portalName, ParameterMap $paramInstance=null)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/settings/portals/'); 
		$apiPath=$apiPath.(strval($portalName)); 
		$apiPath=$apiPath.('/user_type/'); 
		$apiPath=$apiPath.(strval($userTypeId)); 
		$apiPath=$apiPath.('/users'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->setParam($paramInstance); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}
} 
