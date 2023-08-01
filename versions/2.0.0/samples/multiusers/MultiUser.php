<?php
namespace multiusers;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\INDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\Initializer;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\GetRecordsHeader;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class MultiUser
{
	public function main()
    {
		$environment1 = INDataCenter::PRODUCTION();

		$token1 = (new OAuthBuilder())
		->clientId("ClientId")
		->clientSecret("ClientSecret")
		->refreshToken("RefreshToken")
		->redirectURL("RedirectURL")
		->build();

        (new InitializeBuilder())
		->environment($environment1)
		->token($token1)
		->initialize();

        $this->getRecords("Leads");

		$environment2 = USDataCenter::PRODUCTION();

		$token2 = (new OAuthBuilder())
		->clientId("ClientId")
		->clientSecret("ClientSecret")
		->refreshToken("RefreshToken")
		->redirectURL("RedirectURL")
		->build();

        (new InitializeBuilder())
		->environment($environment2)
		->token($token2)
        ->switchUser();

        $this->getRecords("Leads");

         Initializer::removeUserConfiguration($token2);
         Initializer::removeUserConfiguration($token1);

        (new InitializeBuilder())
		->environment($environment1)
		->token($token1)
        ->switchUser();

        $this->getRecords("Accounts");
    }

    public function getRecords($moduleAPIName)
    {
        try
        {
            $recordOperations = new RecordOperations();
            $paramInstance = new ParameterMap();
            $headerInstance = new HeaderMap();
            $fieldNames = array("id", "City");
            foreach ($fieldNames as $fieldName) {
                $paramInstance->add(GetRecordsParam::fields(), $fieldName);
            }
            $ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            $headerInstance->add(GetRecordsHeader::IfModifiedSince(), $ifmodifiedsince);
            //Call getRecord method that takes paramInstance, moduleAPIName as parameter
            $response = $recordOperations->getRecords($moduleAPIName,$paramInstance, $headerInstance);
            echo($response->getStatusCode() . "\n");
            print_r($response);
            echo("\n");
        }
        catch (\Exception $e)
        {
            print_r($e);
        }
    }
}

$obj = new MultiUser();

$obj->main();

?>