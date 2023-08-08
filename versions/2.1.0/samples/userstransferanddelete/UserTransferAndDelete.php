<?php
namespace userstransferanddelete;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\userstransferdelete\APIException;
use com\zoho\crm\api\userstransferdelete\ActionWrapper;
use com\zoho\crm\api\userstransferdelete\BodyWrapper;
use com\zoho\crm\api\userstransferdelete\MoveSubordinate;
use com\zoho\crm\api\userstransferdelete\SuccessResponse;
use com\zoho\crm\api\userstransferdelete\Transfer;
use com\zoho\crm\api\userstransferdelete\TransferAndDelete;
use com\zoho\crm\api\userstransferdelete\UsersTransferDeleteOperations;
use com\zoho\crm\api\util\Choice;

require_once "vendor/autoload.php";

class UserTransferAndDelete 
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

	public static function userTransferAndDelete($id)
	{
		$usersTransferDeleteOperations = new UsersTransferDeleteOperations();
		$request = new BodyWrapper();
		$transferAndDeletes = array();
		$transferAndDelete = new TransferAndDelete();
		$transfer = new Transfer();
		$transfer->setRecords(true);
		$transfer->setAssignment(true);
		$transfer->setCriteria(false);
		$transfer->setId("34349178323");
		$transferAndDelete->setTransfer($transfer);
		$moveSubordinate = new MoveSubordinate();
		$moveSubordinate->setId("323234872984342");
		$transferAndDelete->setMoveSubordinate($moveSubordinate);
		array_push($transferAndDeletes, $transferAndDelete);
		$request->setTransferAndDelete($transferAndDeletes);
		$response = $usersTransferDeleteOperations->userTransferAndDelete($id, $request);
		if ($response != null) {
            echo ("Status Code: " . $response->getStatusCode() . "\n");
			$actionHandler = $response->getObject();
			if ($actionHandler instanceof ActionWrapper)
			{
				$actionWrapper = $actionHandler;
				$actionResponses = $actionWrapper->getTransferAndDelete();
				foreach ($actionResponses as $actionResponse) {
					if ($actionResponse instanceof SuccessResponse){
						$successResponse = $actionResponse;
                        echo ("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo ("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo ("Details: ");
                        if ($successResponse->getDetails() != null) {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue) {
                                echo ($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo ("Message: " . ($successResponse->getMessage() instanceof Choice ? $successResponse->getMessage()->getValue() : $successResponse->getMessage()) . "\n");
					}
					else if ($actionResponse instanceof APIException) {
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
$id = "329874712124";
UserTransferAndDelete::initialize($id);
UserTransferAndDelete::userTransferAndDelete($id);
