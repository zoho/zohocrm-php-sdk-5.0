<?php 
namespace com\zoho\crm\api\contactroles;

use com\zoho\crm\api\Param;

class DeleteRolesParam
{

	public static final function ids()
	{
		return new Param('ids', 'com.zoho.crm.api.ContactRoles.DeleteRolesParam'); 

	}
} 
