<?php 
namespace com\zoho\crm\api\emailcomposemeta;

use com\zoho\crm\api\util\Model;

class User implements Model
{

	private  $fullName;
	private  $email;
	private  $zuid;
	private  $zgid;
	private  $composeSettings;
	private  $keyModified=array();

	/**
	 * The method to get the fullName
	 * @return string A string representing the fullName
	 */
	public  function getFullName()
	{
		return $this->fullName; 

	}

	/**
	 * The method to set the value to fullName
	 * @param string $fullName A string
	 */
	public  function setFullName(string $fullName)
	{
		$this->fullName=$fullName; 
		$this->keyModified['full_name'] = 1; 

	}

	/**
	 * The method to get the email
	 * @return string A string representing the email
	 */
	public  function getEmail()
	{
		return $this->email; 

	}

	/**
	 * The method to set the value to email
	 * @param string $email A string
	 */
	public  function setEmail(string $email)
	{
		$this->email=$email; 
		$this->keyModified['email'] = 1; 

	}

	/**
	 * The method to get the zuid
	 * @return string A string representing the zuid
	 */
	public  function getZuid()
	{
		return $this->zuid; 

	}

	/**
	 * The method to set the value to zuid
	 * @param string $zuid A string
	 */
	public  function setZuid(string $zuid)
	{
		$this->zuid=$zuid; 
		$this->keyModified['zuid'] = 1; 

	}

	/**
	 * The method to get the zgid
	 * @return string A string representing the zgid
	 */
	public  function getZgid()
	{
		return $this->zgid; 

	}

	/**
	 * The method to set the value to zgid
	 * @param string $zgid A string
	 */
	public  function setZgid(string $zgid)
	{
		$this->zgid=$zgid; 
		$this->keyModified['zgid'] = 1; 

	}

	/**
	 * The method to get the composeSettings
	 * @return ComposeSettings An instance of ComposeSettings
	 */
	public  function getComposeSettings()
	{
		return $this->composeSettings; 

	}

	/**
	 * The method to set the value to composeSettings
	 * @param ComposeSettings $composeSettings An instance of ComposeSettings
	 */
	public  function setComposeSettings(ComposeSettings $composeSettings)
	{
		$this->composeSettings=$composeSettings; 
		$this->keyModified['compose_settings'] = 1; 

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
