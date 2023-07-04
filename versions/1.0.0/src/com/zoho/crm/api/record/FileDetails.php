<?php 
namespace com\zoho\crm\api\record;

use com\zoho\crm\api\util\Model;

class FileDetails implements Model
{

	private  $fileNameS;
	private  $sizeS;
	private  $id;
	private  $fileIdS;
	private  $delete;
	private  $keyModified=array();

	/**
	 * The method to get the fileNameS
	 * @return string A string representing the fileNameS
	 */
	public  function getFileNameS()
	{
		return $this->fileNameS; 

	}

	/**
	 * The method to set the value to fileNameS
	 * @param string $fileNameS A string
	 */
	public  function setFileNameS(string $fileNameS)
	{
		$this->fileNameS=$fileNameS; 
		$this->keyModified['File_Name__s'] = 1; 

	}

	/**
	 * The method to get the sizeS
	 * @return string A string representing the sizeS
	 */
	public  function getSizeS()
	{
		return $this->sizeS; 

	}

	/**
	 * The method to set the value to sizeS
	 * @param string $sizeS A string
	 */
	public  function setSizeS(string $sizeS)
	{
		$this->sizeS=$sizeS; 
		$this->keyModified['Size__s'] = 1; 

	}

	/**
	 * The method to get the id
	 * @return string A string representing the id
	 */
	public  function getId()
	{
		return $this->id; 

	}

	/**
	 * The method to set the value to id
	 * @param string $id A string
	 */
	public  function setId(string $id)
	{
		$this->id=$id; 
		$this->keyModified['id'] = 1; 

	}

	/**
	 * The method to get the fileIdS
	 * @return string A string representing the fileIdS
	 */
	public  function getFileIdS()
	{
		return $this->fileIdS; 

	}

	/**
	 * The method to set the value to fileIdS
	 * @param string $fileIdS A string
	 */
	public  function setFileIdS(string $fileIdS)
	{
		$this->fileIdS=$fileIdS; 
		$this->keyModified['File_Id__s'] = 1; 

	}

	/**
	 * The method to get the delete
	 * @return string A string representing the delete
	 */
	public  function getDelete()
	{
		return $this->delete; 

	}

	/**
	 * The method to set the value to delete
	 * @param string $delete A string
	 */
	public  function setDelete(string $delete)
	{
		$this->delete=$delete; 
		$this->keyModified['_delete'] = 1; 

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
