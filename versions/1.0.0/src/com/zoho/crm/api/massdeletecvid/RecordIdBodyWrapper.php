<?php 
namespace com\zoho\crm\api\massdeletecvid;

use com\zoho\crm\api\util\Model;

class RecordIdBodyWrapper implements Model
{

	private  $ids;
	private  $territory;
	private  $keyModified=array();

	/**
	 * The method to get the ids
	 * @return array A array representing the ids
	 */
	public  function getIds()
	{
		return $this->ids; 

	}

	/**
	 * The method to set the value to ids
	 * @param array $ids A array
	 */
	public  function setIds(array $ids)
	{
		$this->ids=$ids; 
		$this->keyModified['ids'] = 1; 

	}

	/**
	 * The method to get the territory
	 * @return Territory An instance of Territory
	 */
	public  function getTerritory()
	{
		return $this->territory; 

	}

	/**
	 * The method to set the value to territory
	 * @param Territory $territory An instance of Territory
	 */
	public  function setTerritory(Territory $territory)
	{
		$this->territory=$territory; 
		$this->keyModified['territory'] = 1; 

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
