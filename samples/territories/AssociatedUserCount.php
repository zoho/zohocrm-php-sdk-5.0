<?php
namespace territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\AssociatedUsersCountWrapper;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class AssociatedUserCount
{
	public static function initialize()
	{
		$environment = USDataCenter::PRODUCTION();
		$token = (new OAuthBuilder())
			->clientId("client_id")
			->clientSecret("client_secret")
			->refreshToken("refresh_token")
			->build();
		(new InitializeBuilder())
			->environment($environment)
			->token($token)
			->initialize();
	}

	public static function getAssociatedUsercount()
	{
		$territoriesOperations = new TerritoriesOperations();
		$response = $territoriesOperations->getAssociatedUserCount();
		if ($response != null) {
			echo ("Status code " . $response->getStatusCode() . "\n");
			if (in_array($response->getStatusCode(), array(204, 304))) {
				echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
				return;
			}
			$responseHandler = $response->getObject();
			if ($responseHandler instanceof AssociatedUsersCountWrapper) {
				$responseWrapper = $responseHandler;
				$territoryList = $responseWrapper->getAssociatedUsersCount();
				foreach ($territoryList as $territorycount) {
					echo ("AssociatedUsersCount count: " . $territorycount->getCount() . "\n");
					$territory = $territorycount->getTerritory();
					if ($territory != null) {
						echo ("AssociatedUsersCount Name" . $territory->getName() . "\n");
						echo ("AssociatedUsersCount ID" . $territory->getId() . "\n");
						echo ("AssociatedUsersCount Subordinates" . $territory->getSubordinates() . "\n");
					}
				}
				$info = $responseWrapper->getInfo();
				echo ("Territory Info PerPage : " . $info->getPerPage() . "\n");
				echo ("Territory Info Count : " . $info->getCount() . "\n");
				echo ("Territory Info Page : " . $info->getPage() . "\n");
				echo ("Territory Info MoreRecords : ");
				print_r($info->getMoreRecords());
				echo ("\n");
			} else if ($responseHandler instanceof APIException) {
				$exception = $responseHandler;
				echo ("Status: " . $exception->getStatus()->getValue() . "\n");
				echo ("Code: " . $exception->getCode()->getValue() . "\n");
				echo ("Details: ");
				foreach ($exception->getDetails() as $key => $value) {
					echo ($key . " : " . $value . "\n");
				}
				echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
			}
		}
	}
}

AssociatedUserCount::initialize();
AssociatedUserCount::getAssociatedUsercount();
