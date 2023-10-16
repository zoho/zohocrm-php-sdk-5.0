<?php
namespace com\zoho\crm\api;

use com\zoho\crm\api\util\Constants;

use com\zoho\crm\api\util\Utility;

use com\zoho\crm\api\exception\SDKException;

/**
 * This class represents the CRM user email.
 */
class UserSignature
{
    private $name;
    /**
     * @throws SDKException
     */
    function __construct($name)
    {
        if(is_null($name))
        {
            Utility::assertNotNull(null , Constants::MANDATORY_VALUE_ERROR, Constants::MANDATORY_KEY_ERROR . " - " . Constants::NAME);
        }
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
}