<?php 
namespace com\zoho\crm\api\usersterritories;

use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class UsersTerritoriesOperations
{

	/**
	 * The method to get territories of user
	 * @param string $user A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getTerritoriesOfUser(string $user)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to associate territories to user
	 * @param string $user A string
	 * @param BodyWrapper $request An instance of BodyWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function associateTerritoriesToUser(string $user, BodyWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_PUT); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_CREATE); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		$handlerInstance->setMandatoryChecker(true); 
		return $handlerInstance->apiCall(ActionHandler::class, 'application/json'); 

	}

	/**
	 * The method to get territory of user
	 * @param string $territory A string
	 * @param string $user A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getTerritoryOfUser(string $territory, string $user)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories/'); 
		$apiPath=$apiPath.(strval($territory)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to validate before transfer for all territories
	 * @param string $user A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function validateBeforeTransferForAllTerritories(string $user)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories/actions/validate_before_transfer'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_ACTION); 
		return $handlerInstance->apiCall(ValidationHandler::class, 'application/json'); 

	}

	/**
	 * The method to validate before transfer
	 * @param string $territory A string
	 * @param string $user A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function validateBeforeTransfer(string $territory, string $user)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories/'); 
		$apiPath=$apiPath.(strval($territory)); 
		$apiPath=$apiPath.('/actions/validate_before_transfer'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_ACTION); 
		return $handlerInstance->apiCall(ValidationHandler::class, 'application/json'); 

	}

	/**
	 * The method to delink and transfer from all territories
	 * @param string $user A string
	 * @param TransferWrapper $request An instance of TransferWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function delinkAndTransferFromAllTerritories(string $user, TransferWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories/actions/transfer_and_delink'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_PUT); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_ACTION); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		return $handlerInstance->apiCall(TransferActionHandler::class, 'application/json'); 

	}

	/**
	 * The method to delink and transfer from specific territory
	 * @param string $territory A string
	 * @param string $user A string
	 * @param TransferWrapper $request An instance of TransferWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function delinkAndTransferFromSpecificTerritory(string $territory, string $user, TransferWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/users/'); 
		$apiPath=$apiPath.(strval($user)); 
		$apiPath=$apiPath.('/territories/'); 
		$apiPath=$apiPath.(strval($territory)); 
		$apiPath=$apiPath.('/actions/transfer_and_delink'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_PUT); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_ACTION); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		return $handlerInstance->apiCall(TransferActionHandler::class, 'application/json'); 

	}
} 
