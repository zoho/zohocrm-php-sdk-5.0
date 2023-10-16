<?php
namespace userstransferanddelete;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\userstransferdelete\APIException;
use com\zoho\crm\api\userstransferdelete\ResponseWrapper;
use com\zoho\crm\api\userstransferdelete\UsersTransferDeleteOperations;
use com\zoho\crm\api\userstransferdelete\GetStatusParam;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class GetStatus
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

	public static function getStatus()
	{
		$usersTransferDeleteOperations = new UsersTransferDeleteOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetStatusParam::jobId(), "32838742872382");
		$response = $usersTransferDeleteOperations->getStatus($paramInstance);
		if ($response != null) {
			echo ("Status Code: " . $response->getStatusCode() . "\n");
			if (in_array($response->getStatusCode(), array(204, 304))) {
				echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
				return;
			}
			$responseHandler = $response->getObject();
			if ($responseHandler instanceof ResponseWrapper) {
				$responseWrapper = $responseHandler;
				$transferAndDelete = $responseWrapper->getTransferAndDelete();
				if ($transferAndDelete != null) {
					foreach ($transferAndDelete as $status) {
						echo ("TransferAndDelete Status: " . $status->getStatus());
					}
				}
			} else if ($responseHandler instanceof APIException) {
				$exception = $responseHandler;
				echo ("Status: " . $exception->getStatus()->getValue() . "\n");
				echo ("Code: " . $exception->getCode()->getValue() . "\n");
				echo ("Details: ");
				foreach ($exception->getDetails() as $key => $value) {
					echo ($key . ": " . $value . "\n");
				}
				echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
			}
		}
	}
}

GetStatus::initialize();
GetStatus::getStatus();
