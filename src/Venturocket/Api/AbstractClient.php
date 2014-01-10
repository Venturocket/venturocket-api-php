<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 1:20 PM
 */

namespace Venturocket\Api;


use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

abstract class AbstractClient {
    const BASE_URL = "https://api.venturocket.com/v1/";

    /**
     * @var string api key
     */
    protected $key;

    /**
     * @var string api secret
     */
    protected $secret;

    /**
     * @param string $key your Venturcoket api key
     * @param string $secret your Venturocket api secret
     */
    public function __construct($key, $secret){
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * Perform a HTTP GET operation
     * @param string $url
     * @param array $queryParams
     * @return array
     */
    protected function get($url, $queryParams = array()){
        return $this->request("GET", $url, $queryParams);
    }

    /**
     * Perform a HTTP POST operation
     * @param string $url
     * @param array $queryParams
     * @param array $data
     * @return array
     */
    protected function post($url, $queryParams = array(), $data = array()){
        return $this->request("POST", $url, $queryParams, $data);
    }

    /**
     * Perform a HTTP PUT request
     * @param string $url
     * @param array $queryParams
     * @param array $data
     * @return array
     */
    protected function put($url, $queryParams = array(), $data = array()){
        return $this->request("PUT", $url, $queryParams, $data);
    }

    /**
     * Send a request to the API
     * @param string $method HTTP request method
     * @param string $url the relative URL of the desired endpoint
     * @param array $queryParams optional query string parameters
     * @param array $data request body data
     * @return array
     * @throws ApiException
     */
    protected function request($method, $url, $queryParams = array(), $data = array()){
        $client = new Client(self::BASE_URL);
        $client->setDefaultOption("auth", array($this->key, $this->secret, "Basic"));
        $headers = array(
            "accept" => "application/json",
            "content-type" => "application/json"
        );
        $request = $client->createRequest($method, $url, $headers, json_encode($data));
        // Add query string parameters to the request
        foreach($queryParams as $key => $value){
            $request->getQuery()->set($key, $value);
        }
        try{
            $response = $request->send();
            $responseBody = json_decode($response->getBody(true), true);
        }
        catch(ClientErrorResponseException $e){
            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            throw new ApiException($e->getResponse()->getStatusCode(), $responseBody['error']);
        }
        return $responseBody;
    }
} 