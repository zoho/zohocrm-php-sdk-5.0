<?php 
namespace com\zoho\crm\api\usertypeusers;

use com\zoho\crm\api\Param;

class GetUsersofUserTypeParam
{

	public static final function filters()
	{
		return new Param('filters', 'com.zoho.crm.api.UserTypeUsers.GetUsersofUserTypeParam'); 

	}
	public static final function type()
	{
		return new Param('type', 'com.zoho.crm.api.UserTypeUsers.GetUsersofUserTypeParam'); 

	}
} 
