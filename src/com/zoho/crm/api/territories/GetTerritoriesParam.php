<?php 
namespace com\zoho\crm\api\territories;

use com\zoho\crm\api\Param;

class GetTerritoriesParam
{

	public static final function filters()
	{
		return new Param('filters', 'com.zoho.crm.api.Territories.GetTerritoriesParam'); 

	}
} 
