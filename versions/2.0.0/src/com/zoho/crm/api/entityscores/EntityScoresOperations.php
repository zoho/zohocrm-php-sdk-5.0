<?php 
namespace com\zoho\crm\api\entityscores;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class EntityScoresOperations
{

	private  $fields;

	/**
	 * Creates an instance of EntityScoresOperations with the given parameters
	 * @param string $fields A string
	 */
	public function __Construct(string $fields=null)
	{
		$this->fields=$fields; 

	}

	/**
	 * The method to get module
	 * @param string $recordId A string
	 * @param string $module A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getModule(string $recordId, string $module)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/'); 
		$apiPath=$apiPath.(strval($module)); 
		$apiPath=$apiPath.('/'); 
		$apiPath=$apiPath.(strval($recordId)); 
		$apiPath=$apiPath.('/Entity_Scores__s'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('fields', 'com.zoho.crm.api.EntityScores.GetModuleParam'), $this->fields); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to get modules
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getModules()
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v5/Entity_Scores__s'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('fields', 'com.zoho.crm.api.EntityScores.GetModulesParam'), $this->fields); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}
} 
