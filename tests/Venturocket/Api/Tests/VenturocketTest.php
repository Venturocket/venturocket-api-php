<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 4:54 PM
 */

namespace Venturocket\Api\Tests;


use Venturocket\Api\Venturocket;

class VenturocketTest extends BaseTest {
    /**
     * @var Venturocket
     */
    protected $client;

    protected function setUp(){
        parent::setUp();
        $this->client = new Venturocket($this->key, $this->secret);
    }

    public function testGetClient(){
        $this->assertInstanceOf('\Venturocket\Api\Keyword\KeywordClient', $this->client->keyword());
    }
}
 