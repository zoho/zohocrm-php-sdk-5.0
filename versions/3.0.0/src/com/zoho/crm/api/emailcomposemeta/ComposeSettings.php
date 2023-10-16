<?php 
namespace com\zoho\crm\api\emailcomposemeta;

use com\zoho\crm\api\util\Model;

class ComposeSettings implements Model
{

	private  $defaultFromAddress;
	private  $fontSize;
	private  $fontFamily;
	private  $emailSignatures;
	private  $keyModified=array();

	/**
	 * The method to get the defaultFromAddress
	 * @return DefaultForm An instance of DefaultForm
	 */
	public  function getDefaultFromAddress()
	{
		return $this->defaultFromAddress; 

	}

	/**
	 * The method to set the value to defaultFromAddress
	 * @param DefaultForm $defaultFromAddress An instance of DefaultForm
	 */
	public  function setDefaultFromAddress(DefaultForm $defaultFromAddress)
	{
		$this->defaultFromAddress=$defaultFromAddress; 
		$this->keyModified['default_from_address'] = 1; 

	}

	/**
	 * The method to get the fontSize
	 * @return int A int representing the fontSize
	 */
	public  function getFontSize()
	{
		return $this->fontSize; 

	}

	/**
	 * The method to set the value to fontSize
	 * @param int $fontSize A int
	 */
	public  function setFontSize(int $fontSize)
	{
		$this->fontSize=$fontSize; 
		$this->keyModified['font_size'] = 1; 

	}

	/**
	 * The method to get the fontFamily
	 * @return string A string representing the fontFamily
	 */
	public  function getFontFamily()
	{
		return $this->fontFamily; 

	}

	/**
	 * The method to set the value to fontFamily
	 * @param string $fontFamily A string
	 */
	public  function setFontFamily(string $fontFamily)
	{
		$this->fontFamily=$fontFamily; 
		$this->keyModified['font_family'] = 1; 

	}

	/**
	 * The method to get the emailSignatures
	 * @return array A array representing the emailSignatures
	 */
	public  function getEmailSignatures()
	{
		return $this->emailSignatures; 

	}

	/**
	 * The method to set the value to emailSignatures
	 * @param array $emailSignatures A array
	 */
	public  function setEmailSignatures(array $emailSignatures)
	{
		$this->emailSignatures=$emailSignatures; 
		$this->keyModified['email_signatures'] = 1; 

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
