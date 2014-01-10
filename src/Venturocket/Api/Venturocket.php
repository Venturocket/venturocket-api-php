<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 4:49 PM
 */

namespace Venturocket\Api;


use Venturocket\Api\AbstractClient;
use Venturocket\Api\Keyword\KeywordClient;
use Venturocket\Api\Listing\ListingClient;

class Venturocket {
    protected $key;

    protected $secret;

    /**
     * @var AbstractClient[]
     */
    protected $clients = array();

    /**
     * @param string $key your api key
     * @param string $secret your api secret
     */
    public function __construct($key, $secret){
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * @return KeywordClient
     */
    public function keyword(){
        return $this->getClient('Keyword\KeywordClient');
    }

    /**
     * @return ListingClient
     */
    public function listing(){
        return $this->getClient('Listing\ListingClient');
    }

    /**
     * @param string $class
     * @return AbstractClient
     */
    protected function getClient($class){
        $class = "\\".__NAMESPACE__."\\".$class;
        if(!array_key_exists($class, $this->clients)){
            $this->clients[$class] = new $class($this->key, $this->secret);
        }
        return $this->clients[$class];
    }
} 