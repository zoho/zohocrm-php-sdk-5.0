<?php
namespace com\zoho\api\authenticator\store;

use com\zoho\api\authenticator\OAuthToken;
use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\api\authenticator\Token;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\util\Constants;

use Exception;

/**
 *
 * This class stores the user token details to the MySQL DataBase.
 *
 */
class DBStore implements TokenStore
{
    private $userName = null;

    private $portNumber = null;

    private $password = null;

    private $host = null;

    private $databaseName = null;

    private $tableName = null;

    /**
     * Create an DBStore class instance with the specified parameters.
     * @param string $host A string containing the DataBase host name.
     * @param string $databaseName A String containing the DataBase name.
     * @param string $tableName A String containing the DataBase table name.
     * @param string $userName A String containing the DataBase user name.
     * @param string $password A String containing the DataBase password.
     * @param string $portNumber A String containing the DataBase port number.
     */
    private function __construct($host, $databaseName, $tableName, $userName, $password, $portNumber)
    {
        $this->host = $host;

        $this->databaseName = $databaseName;

        $this->tableName = $tableName;

        $this->userName = $userName;

        $this->password = $password;

        $this->portNumber = $portNumber;
    }

    public function findToken(Token $token)
    {
        if (!($token instanceof OAuthToken))
        {
            return;
        }

        try
        {
            $oauthToken = $token;
            $query = "select * from " . $this->tableName;

            if ($oauthToken->getUserSignature() != null)
            {
                $name = $oauthToken->getUserSignature()->getName();

                if ($name != null && strlen($name) > 0)
                {
                    $query .= " where user_name='" . $name . "'";
                }
            }
            elseif ($oauthToken->getAccessToken() != null && $oauthToken->getClientId() == null && $oauthToken->getClientSecret() == null)
            {
                $query .= " where access_token='" . $oauthToken->getAccessToken() . "'";
            }
            elseif (($oauthToken->getRefreshToken() != null || $oauthToken->getGrantToken() != null) && $oauthToken->getClientId() != null && $oauthToken->getClientSecret() != null)
            {
                if ($oauthToken->getGrantToken() != null && strlen($oauthToken->getGrantToken()) > 0)
                {
                    $query .= " where grant_token='" . $oauthToken->getGrantToken() . "'";
                }
                elseif ($oauthToken->getRefreshToken() != null && strlen($oauthToken->getRefreshToken()) > 0)
                {
                    $query .= " where refresh_token='" . $oauthToken->getRefreshToken() . "'";
                }
            }
            $query .= "limit 1;";

            try
            {
                $connection = $this->getMysqlConnection();

                $result = mysqli_query($connection, $query);

                if (!$result)
                {
                    return null;
                }
                do
                {
                    $this->setMergeData($oauthToken, $result);
                    break;
                }
                while (next($result));
            }
            catch (Exception $e)
            {
                throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKEN_DB_ERROR1. $e);
            }
            finally
            {
                if ($connection != null)
                {
                    $connection->close();
                }
            }
        }
        catch (Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKEN_DB_ERROR1. $ex);
        }
        return $token;
    }

    public function saveToken(Token $token)
    {
        if (!($token instanceof OAuthToken))
        {
            return;
        }

        $oauthToken = $token;
        $query = "update " . $this->tableName . " set ";
        if ($oauthToken->getUserSignature() != null)
        {
            $name = $oauthToken->getUserSignature()->getName();

            if ($name != null && strlen($name) > 0)
            {
                $query .= $this->setToken($oauthToken) . " where user_name='" . $name . "'";
            }
        }
        elseif ($oauthToken->getAccessToken() != null && strlen($oauthToken->getAccessToken()) > 0 && $oauthToken->getClientId() == null && $oauthToken->getClientSecret() == null)
        {
            $query .= $this->setToken($oauthToken) . " where access_token='" . $oauthToken->getAccessToken() . "'";
        }
        elseif ((($oauthToken->getRefreshToken() != null && strlen($oauthToken->getRefreshToken()) > 0) || ($oauthToken->getGrantToken() != null && strlen($oauthToken->getGrantToken()) > 0)) && $oauthToken->getClientId() != null && $oauthToken->getClientSecret() != null)
        {
            if ($oauthToken->getGrantToken() != null && strlen($oauthToken->getGrantToken()) > 0)
            {
                $query .= $this->setToken($oauthToken) . " where grant_token='" . $oauthToken->getGrantToken() . "'";
            }
            else if ($oauthToken->getRefreshToken() != null && strlen($oauthToken->getRefreshToken())> 0)
            {
                $query .= $this->setToken($oauthToken) . " where refresh_token='" . $oauthToken->getRefreshToken() . "'";
            }
        }
        $query .= " limit 1";
        try
        {
            $rowaffected = false;

            $connection = $this->getMysqlConnection();

            $connection->query($query);

            if ($connection->affected_rows > 0)
            {
                $rowaffected = true;
            }
            if (!$rowaffected)
            {
                if ($oauthToken->getId() != null || $oauthToken->getUserSignature() != null) 
                {
                    if ($oauthToken->getRefreshToken() == null && $oauthToken->getGrantToken() == null && $oauthToken->getAccessToken() == null) 
                    {
                        throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKEN_DB_ERROR1);
                    }
                }
                if ($oauthToken->getId() == null)
                {
                    $newId = strval($this->generateId());

                    $oauthToken->setId($newId);
                }
                try
                {
                    $stmt = mysqli_prepare($connection,	"insert into " . $this->tableName . "(id,user_name,client_id,client_secret,refresh_token,access_token,grant_token,expiry_time,redirect_url) values(?,?,?,?,?,?,?,?,?) on duplicate key update user_name=values(user_name),client_id=values(client_id),client_secret=values(client_secret),refresh_token=values(refresh_token),access_token=values(access_token),grant_token=values(grant_token),expiry_time=values(expiry_time),redirect_url=values(redirect_url);");

                    $id = $oauthToken->getId();

                    $user_name = $oauthToken->getUserSignature() != null ? $oauthToken->getUserSignature()->getName() : null;

                    $clientId = $oauthToken->getClientId();

                    $clientSecret = $oauthToken->getClientSecret();

                    $refreshToken = $oauthToken->getRefreshToken();

                    $accessToken = $oauthToken->getAccessToken();

                    $grantToken = $oauthToken->getGrantToken();

                    $expiresIn = $oauthToken->getExpiresIn();

                    $redirectURL = $oauthToken->getRedirectURL();

                    $stmt->bind_param('sssssssss', $id, $user_name, $clientId, $clientSecret, $refreshToken, $accessToken, $grantToken, $expiresIn, $redirectURL);

                    $stmt->execute();
                }
                catch (Exception $ex)
                {
                    throw $ex;
                }
            }
        }
        catch (Exception $e)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::SAVE_TOKEN_DB_ERROR, $e);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }
    }

    public function deleteToken($id)
    {
        try {
            $connection = $this->getMysqlConnection();

            $query = "delete from " . $this->tableName . " where id='" . $id . "';";

            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                throw new \Exception($connection->error);
            }
        }
        catch (SDKException $ex)
        {
            throw $ex;
        }
        catch (\Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::DELETE_TOKEN_DB_ERROR, null, $ex);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }
    }

    private function getMysqlConnection()
    {
        $mysqli_con = new \mysqli($this->host . ":" . $this->portNumber, $this->userName, $this->password, $this->databaseName);

        if ($mysqli_con->connect_errno)
        {
            throw new \Exception($mysqli_con->connect_error);
        }

        return $mysqli_con;
    }

    public function getTokens()
    {
        $connection = null;

        $tokens = array();

        try
        {
            $connection = $this->getMysqlConnection();

            $query = "select * from " . $this->tableName . ";";

            $result = mysqli_query($connection, $query);

            if ($result)
            {
                while ($row = mysqli_fetch_assoc($result))
                {
                    $grantToken = ($row[Constants::GRANT_TOKEN] !== null && $row[Constants::GRANT_TOKEN] !== Constants::NULL_VALUE && strlen($row[Constants::GRANT_TOKEN]) > 0) ? $row[Constants::GRANT_TOKEN] : null;

                    $token = (new OAuthBuilder())->clientId($row[Constants::CLIENT_ID])->clientSecret($row[Constants::CLIENT_SECRET])->refreshToken($row[Constants::REFRESH_TOKEN])->build();

                    $token->setId($row[Constants::ID]);

                    if($grantToken != null)
                    {
                        $token->setGrantToken($grantToken);
                    }
                    $name = $row[Constants::USER_NAME];
                    if ($name != null)
                    {
                        $token->setUserSignature(new UserSignature($name));
                    }

                    $token->setAccessToken($row[Constants::ACCESS_TOKEN]);

                    $token->setExpiresIn($row[Constants::EXPIRY_TIME]);

                    $token->setRedirectURL($row[Constants::REDIRECT_URL]);

                    $tokens[] = $token;
                }
            }
        }
        catch (\Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKENS_DB_ERROR, null, $ex);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }

        return $tokens;
    }

    public function deleteTokens()
    {
        $connection = null;

        try
        {
            $connection = $this->getMysqlConnection();

            $query = "delete from " . $this->tableName . ";";

            mysqli_query($connection, $query);

        }
        catch (\Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::DELETE_TOKENS_DB_ERROR, null, $ex);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }
    }

    public function findTokenById($id)
    {
        try
        {
            $connection = $this->getMysqlConnection();

            $class = new \ReflectionClass(OAuthToken::class);

            $oauthToken = $class->newInstanceWithoutConstructor();

            $query = "select * from " . $this->tableName . " where id='" . $id . "';";

            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKEN_BY_ID_DB_ERROR);
            }
            do
            {
                $this->setMergeData($oauthToken, $result);

                return $oauthToken;
            }
            while (next($result));
        }
        catch (SDKException $ex)
        {
            throw $ex;
        }
        catch (Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::GET_TOKEN_BY_ID_DB_ERROR, $ex);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }
    }

    private function setMergeData(OAuthToken $oauthToken, \mysqli_result $result)
    {
        if ($result)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                if ($oauthToken->getId() == null)
                {
                    $oauthToken->setId($row[Constants::ID]);
                }
                if ($oauthToken->getUserSignature() == null)
                {
                    $name = $row[Constants::USER_NAME];
                    if ($name != null)
                    {
                        $oauthToken->setUserSignature(new UserSignature($name));
                    }
                }
                if ($oauthToken->getClientId() == null)
                {
                    $oauthToken->setClientId($row[Constants::CLIENT_ID]);
                }
                if ($oauthToken->getClientSecret() == null)
                {
                    $oauthToken->setClientSecret($row[Constants::CLIENT_SECRET]);
                }
                if ($oauthToken->getRefreshToken() == null)
                {
                    $oauthToken->setRefreshToken($row[Constants::REFRESH_TOKEN]);
                }
                if ($oauthToken->getAccessToken() == null)
                {
                    $oauthToken->setAccessToken($row[Constants::ACCESS_TOKEN]);
                }
                if ($oauthToken->getGrantToken() == null)
                {
                    $oauthToken->setGrantToken($row[Constants::GRANT_TOKEN]);
                }
                if ($oauthToken->getExpiresIn() == null)
                {
                    $expiresIn = $row[Constants::EXPIRY_TIME];
                    if ($expiresIn != null)
                    {
                        $oauthToken->setExpiresIn(strval($expiresIn));
                    }
                }
                if ($oauthToken->getRedirectURL() == null)
                {
                    $oauthToken->setRedirectURL($row[Constants::REDIRECT_URL]);
                }
            }
        }
    }

    private function setToken(OAuthToken $oauthToken)
    {
        $query = array();

        if ($oauthToken->getUserSignature() != null)
        {
            $name = $oauthToken->getUserSignature()->getName();
            if ($name != null && strlen($name) > 0)
            {
                array_push($query, $this->setTokenInfo(Constants::USER_NAME, $name));
            }
        }
        if ($oauthToken->getClientId() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::CLIENT_ID, $oauthToken->getClientId()));
        }
        if ($oauthToken->getClientSecret() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::CLIENT_SECRET, $oauthToken->getClientSecret()));
        }
        if ($oauthToken->getRefreshToken() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::REFRESH_TOKEN, $oauthToken->getRefreshToken()));
        }
        if ($oauthToken->getAccessToken() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::ACCESS_TOKEN, $oauthToken->getAccessToken()));
        }
        if ($oauthToken->getGrantToken() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::GRANT_TOKEN, $oauthToken->getGrantToken()));
        }
        if ($oauthToken->getExpiresIn() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::EXPIRY_TIME, $oauthToken->getExpiresIn()));
        }
        if ($oauthToken->getRedirectURL() != null)
        {
            array_push($query, $this->setTokenInfo(Constants::REDIRECT_URL, $oauthToken->getRedirectURL()));
        }
        return join(",", $query);
    }

    public function setTokenInfo($key, $value)
    {
        return $key . "='" . $value . "'";
    }

    private function generateId()
    {
        $id = 0;
        try
        {
            $connection = $this->getMysqlConnection();

            $query = "select coalesce(max(id), 0) as id from " . $this->tableName . ";";

            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                return $id;
            }
            do
            {
                $max = $this->getMax($result);
                if ($max != null)
                {
                    return $max + 1;
                }
            }
            while (next($result));
        }
        catch (Exception $ex)
        {
            throw new SDKException(Constants::TOKEN_STORE, Constants::GENERATE_TOKEN_ID_ERROR, $ex);
        }
        finally
        {
            if ($connection != null)
            {
                $connection->close();
            }
        }
    }
    public function getMax(\mysqli_result $result)
    {
        $max = 0;
        if($result)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $max = $row[Constants::ID];
            }
        }
        return $max;
    }
}
?>