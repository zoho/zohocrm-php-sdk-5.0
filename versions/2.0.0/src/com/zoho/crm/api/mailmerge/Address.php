<?php 
namespace com\zoho\crm\api\mailmerge;

use com\zoho\crm\api\util\Model;

class Address implements Model
{

	private  $addressValueMap;
	private  $keyModified=array();

	/**
	 * The method to get the addressValueMap
	 * @return AddressValueMap An instance of AddressValueMap
	 */
	public  function getAddressValueMap()
	{
		return $this->addressValueMap; 

	}

	/**
	 * The method to set the value to addressValueMap
	 * @param AddressValueMap $addressValueMap An instance of AddressValueMap
	 */
	public  function setAddressValueMap(AddressValueMap $addressValueMap)
	{
		$this->addressValueMap=$addressValueMap; 
		$this->keyModified['Address_Value_Map'] = 1; 

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
