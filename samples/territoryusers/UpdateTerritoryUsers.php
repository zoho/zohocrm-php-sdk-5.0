<?php
namespace territoryusers;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territoryusers\APIException;
use com\zoho\crm\api\territoryusers\ActionWrapper;
use com\zoho\crm\api\territoryusers\BodyWrapper;
use com\zoho\crm\api\territoryusers\SuccessResponse;
use com\zoho\crm\api\territoryusers\TerritoryUsersOperations;
use com\zoho\crm\api\users\Users;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateTerritoryUsers
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

	public static function updateTerritoryusers($territory)
	{
		$territoryUsersOperations = new TerritoryUsersOperations();
		$request = new BodyWrapper();
		$users = array();
		$user = new Users();
		$user->setId("34770615791024");
		array_push($users, $user);
		$request->setUsers($users);
		$response = $territoryUsersOperations->updateTerritoryUsers($territory, $request);
		if ($response != null) {
			echo ("Status Code: " . $response->getStatusCode() . "\n");
			$actionHandler = $response->getObject();
			if ($actionHandler instanceof ActionWrapper) {
				$responseWrapper = $actionHandler;
				$actionResponses = $responseWrapper->getUsers();
				foreach ($actionResponses as $actionResponse) {
					if ($actionResponse instanceof SuccessResponse) {
						$successResponse = $actionResponse;
						echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
						echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
						echo ("Details: ");
						foreach ($successResponse->getDetails() as $key => $value) {
							echo ($key . " : " . $value . "\n");
						}
						echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
					} else if ($actionResponse instanceof APIException) {
						$exception = $actionResponse;
						echo ("Status: " . $exception->getStatus()->getValue() . "\n");
						echo ("Code: " . $exception->getCode()->getValue() . "\n");
						echo ("Details: ");
						if ($exception->getDetails() != null) {
							foreach ($exception->getDetails() as $keyName => $keyValue) {
								echo ($keyName . ": " . $keyValue . "\n");
							}
						}
						echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()) . "\n");
					}
				}
			} else if ($actionHandler instanceof APIException) {
				$exception = $actionHandler;
				echo ("Status: " . $exception->getStatus()->getValue() . "\n");
				echo ("Code: " . $exception->getCode()->getValue() . "\n");
				if ($exception->getDetails() != null) {
					echo ("Details: \n");
					foreach ($exception->getDetails() as $keyName => $keyValue) {
						echo ($keyName . ": " . $keyValue . "\n");
					}
				}
				echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
			}
		}
	}
}
$territory = "34770613051397";
UpdateTerritoryUsers::initialize();
UpdateTerritoryUsers::updateTerritoryusers($territory);
