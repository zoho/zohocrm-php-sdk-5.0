<?php 
namespace com\zoho\crm\api\portalusertype;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class PortalUserTypeOperations
{

	private  $portal;

	/**
	 * Creates an instance of PortalUserTypeOperations with the given parameters
	 * @param string $portal A string
	 */
	public function __Construct(string $portal)
	{
		$this->portal=$portal; 

	}

	/**
	 * The method to get user types
	 * @param ParameterMap $paramInstance An instance of ParameterMap
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getUserTypes(ParameterMap $paramInstance=null)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/settings/portals/'); 
		$apiPath=$apiPath.(strval($this->portal)); 
		$apiPath=$apiPath.('/user_type'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->setParam($paramInstance); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to get user type
	 * @param string $userTypeId A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getUserType(string $userTypeId)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/settings/portals/'); 
		$apiPath=$apiPath.(strval($this->portal)); 
		$apiPath=$apiPath.('/user_type/'); 
		$apiPath=$apiPath.(strval($userTypeId)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}
} 
