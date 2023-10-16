<?php 
namespace com\zoho\crm\api\fields;

use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\util\Model;

class Formula implements Model
{

	private  $returnType;
	private  $expression;
	private  $keyModified=array();

	/**
	 * The method to get the returnType
	 * @return Choice An instance of Choice
	 */
	public  function getReturnType()
	{
		return $this->returnType; 

	}

	/**
	 * The method to set the value to returnType
	 * @param Choice $returnType An instance of Choice
	 */
	public  function setReturnType(Choice $returnType)
	{
		$this->returnType=$returnType; 
		$this->keyModified['return_type'] = 1; 

	}

	/**
	 * The method to get the expression
	 * @return Choice An instance of Choice
	 */
	public  function getExpression()
	{
		return $this->expression; 

	}

	/**
	 * The method to set the value to expression
	 * @param Choice $expression An instance of Choice
	 */
	public  function setExpression(Choice $expression)
	{
		$this->expression=$expression; 
		$this->keyModified['expression'] = 1; 

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
