<?php
namespace users;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\users\APIException;
use com\zoho\crm\api\users\AssociatedGroupsWrapper;
use com\zoho\crm\api\users\UsersOperations;

require_once "vendor/autoload.php";

class GetAssociatedGroups 
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

	public static function getAssociatedGroups($userId)
	{
		$usersOperations = new UsersOperations();
		$response = $usersOperations->getAssociatedGroups($userId);
		if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
			$responseHandler = $response->getObject();
			if ($responseHandler instanceof AssociatedGroupsWrapper)
			{
				$associatedGroupsWrapper = $responseHandler;
				$userGroups = $associatedGroupsWrapper->getUserGroups();
				if ($userGroups != null)
				{
					foreach ($userGroups as $userGroup)
					{
						echo("AssociateGroup ID : " . $userGroup->getId() . "\n");
						echo("AssociateGroup Name : " . $userGroup->getName() . "\n");
						echo("AssociateGroup Description : " . $userGroup->getDescription() . "\n");
						echo("AssociateGroup CreatedTime : "); print_r($userGroup->getCreatedTime());  echo("\n");
						echo("AssociateGroup ModifiedTime : "); print_r($userGroup->getModifiedTime()); echo("\n");
						$createdBy = $userGroup->getCreatedBy();
						if ($createdBy != null)
						{
							echo("AssociateGroup CreatedBy ID : " . $userGroup->getId() . "\n");
							echo("AssociateGroup CreatedBy Name : " . $userGroup->getName() . "\n");
						}
						$modifiedBy = $userGroup->getModifiedBy();
						if ($modifiedBy != null)
						{
							echo("AssociateGroup modifiedBy ID : " . $userGroup->getId() . "\n");
							echo("AssociateGroup modifiedBy Name : " . $userGroup->getName() . "\n");
						}
					}
				}					
			}
            else if ($responseHandler instanceof APIException) {
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

$userId = "347706118959001";
GetAssociatedGroups::initialize();
GetAssociatedGroups::getAssociatedGroups($userId);
