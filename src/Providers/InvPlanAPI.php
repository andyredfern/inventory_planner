<?php

namespace Andyredfern\Invplan\Providers;
use Andyredfern\Invplan\Interfaces\ApiInterface;
use Andyredfern\Invplan\Exceptions\InvplanException;
use \GuzzleHttp\Client;

class InvPlanAPI implements ApiInterface
{

    /**
     * 
     *
     * @var string The Inventory Planner API access key 
     */
    private static $apiKey = null;

    /**
     * 
     *
     * @var string The Inventory Planner Account ID 
     */
    private static $accountId = null;

    /**
     * 
     *
     * @var string The Inventory Planner base url 
     */
    private static  $baseUrl = null;

    /**
     * 
     *
     * @var string The Inventory Planner API version 
     */
    private static $apiVersion = null;

    /**
     * Create a new API Interface
     *                              */
    public function __construct($token = null,$account = null)
    {
        if ($token === null) {
            if (self::$apiKey === null) {
                self::$apiKey = getenv('API_KEY');
                if (self::$apiKey === null) {
                    $msg = 'No token provided and none is globally set.';
                    $msg .= 'See Inventory Planner help for details of how to create an API key';
                    throw new InvplanException($msg);
                }
            }
        } else {
            self::validateToken($token);
            $this->apiKey = $token;
        }
        if ($account === null) {
            if (self::$accountId === null) {
                self::$accountId = getenv('ACCOUNT');
                if (self::$accountId === null) {
                    $msg = 'No account number provided, and none is globally set. ';
                    $msg .= 'See Inventory Planner help for details of how to find your API key.';
                    throw new InvplanException($msg);
                }
            }
        } else {
            self::validateAccount($account);
            $this->accountId = $account;
        }
        self::$baseUrl = getenv('BASE_URL');
        if (self::$baseUrl === null) {
            $msg = 'Base URL should be set in the environment file. ';
            $msg .= 'See Inventory Planner help for details of how to find your API key.';
            throw new InvplanException($msg);
        }
        self::$apiVersion = getenv('API_VERSION');
        if (self::$apiVersion === null) {
            $msg = 'API version needs to be set in the environment file. ';
            $msg .= 'See Inventory Planner help for details of how to find your API version.';
            throw new InvplanException($msg);
        }
    }

    public function getResource($resourceType)
    {
        $client = new \GuzzleHttp\Client();

        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType;
        try {
            $res = $client->request(
                'GET', $url, [
                'headers' => [
                    'Authorization' => self::$apiKey,
                    'Account'     => self::$accountId
                ]
                ]
            );
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($res->getBody(), true);
        
    }

    public function patchResource($resourceType, $data_array)
    {
        $client = new \GuzzleHttp\Client();

        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType;
        try {
            $res = $client->request(
                'PATCH', $url, [
                'headers' => [
                    'Authorization' => self::$apiKey,
                    'Account'     => self::$accountId,
                    'Accept'    => 'application/json',
                ],
                'json' => $data_array
                ]
            );
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($res->getBody(), true);
        
    }

    public function putResource($resourceType, $data_array)
    {
  
        $client = new \GuzzleHttp\Client();

        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType;
        
        try {
            $res = $client->request(
                'PUT', $url, [
                'headers' => [
                    'Authorization' => self::$apiKey,
                    'Account'     => self::$accountId,
                    'Accept'    => 'application/json',
                ],
                'json' => $data_array
                ]
            );
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($res->getBody(), true);
        
    }

    public function postResource($resourceType, $data_array)
    {
  
        $client = new \GuzzleHttp\Client();

        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType;
        
        try {
            $res = $client->request(
                'POST', $url, [
                'headers' => [
                    'Authorization' => self::$apiKey,
                    'Account'     => self::$accountId,
                    'Accept'    => 'application/json',
                ],
                'json' => $data_array
                ]
            );
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($res->getBody(), true); 
    }

    public function postFileResource($resourceType, $filename)
    {
  
        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType;
        
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('name'=> new \CURLFILE($filename)),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: '. self::$apiKey,
                    'Account: '.self::$accountId
                ), 
            ));
        
            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($response, true); 
    }

    public function deleteResource($resourceType, $resourceId)
    {
  
        $client = new \GuzzleHttp\Client();

        $url = self::$baseUrl . self::$apiVersion . "/" . $resourceType . "/" . $resourceId;
        
        try {
            $res = $client->request(
                'DELETE', $url, [
                'headers' => [
                    'Authorization' => self::$apiKey,
                    'Account'     => self::$accountId,
                ],
                ]
            );
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return json_decode($res->getBody(), true); 
    }


    /**
     * Validate the api key token is valid data type
     *
     * @param string $token Phrase to return
     *
     * @return string Returns the phrase passed in
     */

    private static function validateToken($token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('Token is not a string.');
        }
        return true;
    }

    /**
     * Validate the api key token is valid data type
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */

    private static function validateAccount($account)
    {
        if (!is_string($account)) {
            throw new \InvalidArgumentException('Token is not a string.');
        }
        return true;
    }


    /**
     * Functionless call to check basic request is available
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function isAlive($phrase)
    {
        if($phrase=="Working?") {
            return $phrase . " Yes I'm working";
        } else {

        }
    }
}
