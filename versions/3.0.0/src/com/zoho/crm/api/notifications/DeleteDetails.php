<?php 
namespace com\zoho\crm\api\notifications;

use com\zoho\crm\api\util\Model;

class DeleteDetails implements Model
{

	private  $resourceId;
	private  $resourceUri;
	private  $channelId;
	private  $keyModified=array();

	/**
	 * The method to get the resourceId
	 * @return string A string representing the resourceId
	 */
	public  function getResourceId()
	{
		return $this->resourceId; 

	}

	/**
	 * The method to set the value to resourceId
	 * @param string $resourceId A string
	 */
	public  function setResourceId(string $resourceId)
	{
		$this->resourceId=$resourceId; 
		$this->keyModified['resource_id'] = 1; 

	}

	/**
	 * The method to get the resourceUri
	 * @return string A string representing the resourceUri
	 */
	public  function getResourceUri()
	{
		return $this->resourceUri; 

	}

	/**
	 * The method to set the value to resourceUri
	 * @param string $resourceUri A string
	 */
	public  function setResourceUri(string $resourceUri)
	{
		$this->resourceUri=$resourceUri; 
		$this->keyModified['resource_uri'] = 1; 

	}

	/**
	 * The method to get the channelId
	 * @return string A string representing the channelId
	 */
	public  function getChannelId()
	{
		return $this->channelId; 

	}

	/**
	 * The method to set the value to channelId
	 * @param string $channelId A string
	 */
	public  function setChannelId(string $channelId)
	{
		$this->channelId=$channelId; 
		$this->keyModified['channel_id'] = 1; 

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
