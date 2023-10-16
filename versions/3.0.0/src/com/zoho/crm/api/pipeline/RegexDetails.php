<?php 
namespace com\zoho\crm\api\pipeline;

use com\zoho\crm\api\util\Model;

class RegexDetails implements Model
{

	private  $apiName;
	private  $jsonPath;
	private  $regex;
	private  $keyModified=array();

	/**
	 * The method to get the aPIName
	 * @return string A string representing the apiName
	 */
	public  function getAPIName()
	{
		return $this->apiName; 

	}

	/**
	 * The method to set the value to aPIName
	 * @param string $apiName A string
	 */
	public  function setAPIName(string $apiName)
	{
		$this->apiName=$apiName; 
		$this->keyModified['api_name'] = 1; 

	}

	/**
	 * The method to get the jsonPath
	 * @return string A string representing the jsonPath
	 */
	public  function getJsonPath()
	{
		return $this->jsonPath; 

	}

	/**
	 * The method to set the value to jsonPath
	 * @param string $jsonPath A string
	 */
	public  function setJsonPath(string $jsonPath)
	{
		$this->jsonPath=$jsonPath; 
		$this->keyModified['json_path'] = 1; 

	}

	/**
	 * The method to get the regex
	 * @return string A string representing the regex
	 */
	public  function getRegex()
	{
		return $this->regex; 

	}

	/**
	 * The method to set the value to regex
	 * @param string $regex A string
	 */
	public  function setRegex(string $regex)
	{
		$this->regex=$regex; 
		$this->keyModified['regex'] = 1; 

	}

	/**
	 * The method to check if the user has modified the given key
	 * @param string $key A string
	 * @return int A int representing the modification
	 */
	public  function isKeyModified(string $key)
	{
		if(((array_key_exists($key, $this->keyModified))))
		{
			return $this->keyModified[$key]; 

		}
		return null; 

	}

	/**
	 * The method to mark the given key as modified
	 * @param string $key A string
	 * @param int $modification A int
	 */
	public  function setKeyModified(string $key, int $modification)
	{
		$this->keyModified[$key] = $modification; 

	}
} 
