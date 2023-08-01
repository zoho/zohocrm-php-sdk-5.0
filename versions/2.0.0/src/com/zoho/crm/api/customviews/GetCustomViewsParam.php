<?php 
namespace com\zoho\crm\api\customviews;

use com\zoho\crm\api\Param;

class GetCustomViewsParam
{

	public static final function module()
	{
		return new Param('module', 'com.zoho.crm.api.CustomViews.GetCustomViewsParam'); 

	}
} 
