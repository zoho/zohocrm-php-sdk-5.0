<?php 
namespace com\zoho\crm\api\backup;

use com\zoho\crm\api\Param;

class HistoryParam
{

	public static final function page()
	{
		return new Param('page', 'com.zoho.crm.api.Backup.HistoryParam'); 

	}
	public static final function perPage()
	{
		return new Param('per_page', 'com.zoho.crm.api.Backup.HistoryParam'); 

	}
} 
