<?php
namespace territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\SuccessResponse;
use com\zoho\crm\api\territories\ActionWrapper;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\territories\DeleteTerritoriesParam;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class DeleteTerritory
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

	public static function deleteTerritory($id)
	{
		$territoriesOperations = new TerritoriesOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(DeleteTerritoriesParam::deletePreviousForecasts(), false);
		$response = $territoriesOperations->deleteTerritory($id, $paramInstance);
		if ($response != null) {
			echo ("Status Code: " . $response->getStatusCode() . "\n");
			$actionHandler = $response->getObject();
			if ($actionHandler instanceof ActionWrapper) {
				$responseWrapper = $actionHandler;
				$actionResponses = $responseWrapper->getTerritories();
				if ($actionResponses != null) {
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

$id = "347706118981006";
DeleteTerritory::initialize();
DeleteTerritory::deleteTerritory($id);
