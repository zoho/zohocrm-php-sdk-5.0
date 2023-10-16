<?php
namespace territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\BodyWrapper;
use com\zoho\crm\api\territories\Criteria;
use com\zoho\crm\api\territories\Field;
use com\zoho\crm\api\territories\Manager;
use com\zoho\crm\api\territories\ReportingTo;
use com\zoho\crm\api\territories\SuccessResponse;
use com\zoho\crm\api\territories\ActionWrapper;
use com\zoho\crm\api\territories\Territories;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UpdateTerritory
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

	public static function updateTerritory($id)
	{
		$territoriesOperations = new TerritoriesOperations();
		$request = new BodyWrapper();
		$territories = array();
		$territory = new Territories();
		$territory->setName("territoryName123");
		$criteria = new Criteria();
		$criteria->setComparator("equal");
		$criteria->setValue("name");
		$field = new Field();
		$field->setAPIName("Account_Name");
		$field->setId("440248001310017");
		$criteria->setField($field);
		$territory->setAccountRuleCriteria($criteria);
		$criteria1 = new Criteria();
		$criteria1->setComparator("not_between");
		$value = array();
		array_push($value, "2023-08-10");
		array_push($value, "2023-08-30");
		$criteria1->setValue($value);
		$field1 = new Field();
		$field1->setAPIName("Closing_Date");
		$field1->setId("323213231223411");
		$criteria1->setField($field1);
		$territory->setDealRuleCriteria($criteria1);
		$territory->setDescription("description");
		$territory->setPermissionType(new Choice("read_only"));
		$reportingTo = new ReportingTo();
		$reportingTo->setId("34770613051397");
		$territory->setReportingTo($reportingTo);
		$manager = new Manager();
		$manager->setId("34770615791024");
		$territory->setManager($manager);
		array_push($territories, $territory);
		$request->setTerritories($territories);
		$response = $territoriesOperations->updateTerritory($id, $request);
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

$id = "347706118974012";
UpdateTerritory::initialize();
UpdateTerritory::updateTerritory($id);