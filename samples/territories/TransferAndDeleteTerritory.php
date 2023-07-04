<?php
namespace territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\SuccessResponse;
use com\zoho\crm\api\territories\ActionWrapper;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\territories\TransferBodyWrapper;
use com\zoho\crm\api\territories\TransferTerritory;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class TransferAndDeleteTerritory
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

	public static function transferAndDeleteTerritory($id)
	{
		$territoriesOperations = new TerritoriesOperations();
		$request = new TransferBodyWrapper();
		$territories = array();
		$territory = new TransferTerritory();
		$territory->setTransferToId("34770613051397");
		$territory->setDeletePreviousForecasts(false);
		array_push($territories, $territory);
		$request->setTerritories($territories);
		$response = $territoriesOperations->transferAndDeleteTerritory($id, $request);
		if ($response != null) {
			echo ("Status Code: " . $response->getStatusCode() . "\n");
			$actionHandler = $response->getObject();
			if ($actionHandler instanceof ActionWrapper) {
				$responseWrapper = $actionHandler;
				$actionResponses = $responseWrapper->getTerritories();
				if ($actionResponses != null)
				{
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
			}
			else if ($actionHandler instanceof APIException) {
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

$id = "347706118964012";
TransferAndDeleteTerritory::initialize();
TransferAndDeleteTerritory::transferAndDeleteTerritory($id);
