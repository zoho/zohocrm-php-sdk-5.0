<?php 
namespace com\zoho\crm\api\blueprint;

use com\zoho\crm\api\util\Model;

class ConvertMapping implements Model
{

	private  $contacts;
	private  $deals;
	private  $accounts;
	private  $keyModified=array();

	/**
	 * The method to get the contacts
	 * @return ModuleMapping An instance of ModuleMapping
	 */
	public  function getContacts()
	{
		return $this->contacts; 

	}

	/**
	 * The method to set the value to contacts
	 * @param ModuleMapping $contacts An instance of ModuleMapping
	 */
	public  function setContacts(ModuleMapping $contacts)
	{
		$this->contacts=$contacts; 
		$this->keyModified['Contacts'] = 1; 

	}

	/**
	 * The method to get the deals
	 * @return ModuleMapping An instance of ModuleMapping
	 */
	public  function getDeals()
	{
		return $this->deals; 

	}

	/**
	 * The method to set the value to deals
	 * @param ModuleMapping $deals An instance of ModuleMapping
	 */
	public  function setDeals(ModuleMapping $deals)
	{
		$this->deals=$deals; 
		$this->keyModified['Deals'] = 1; 

	}

	/**
	 * The method to get the accounts
	 * @return ModuleMapping An instance of ModuleMapping
	 */
	public  function getAccounts()
	{
		return $this->accounts; 

	}

	/**
	 * The method to set the value to accounts
	 * @param ModuleMapping $accounts An instance of ModuleMapping
	 */
	public  function setAccounts(ModuleMapping $accounts)
	{
		$this->accounts=$accounts; 
		$this->keyModified['Accounts'] = 1; 

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
