<?php 
namespace com\zoho\crm\api\scoringrules;

use com\zoho\crm\api\Param;

class GetScoringRuleParam
{

	public static final function layoutId()
	{
		return new Param('layout_id', 'com.zoho.crm.api.ScoringRules.GetScoringRuleParam'); 

	}
	public static final function active()
	{
		return new Param('active', 'com.zoho.crm.api.ScoringRules.GetScoringRuleParam'); 

	}
	public static final function name()
	{
		return new Param('name', 'com.zoho.crm.api.ScoringRules.GetScoringRuleParam'); 

	}
} 
