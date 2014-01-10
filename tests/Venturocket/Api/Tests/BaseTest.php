<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 2:09 PM
 */

namespace Venturocket\Api\Tests;


class BaseTest extends \PHPUnit_Framework_TestCase{
    protected $key = "";

    protected $secret = "";

    protected function setUp(){
        parent::setUp();
        if($config = parse_ini_file(__DIR__.'/tests.cfg', true)){
            $this->key = $config['credentials']['key'];
            $this->secret = $config['credentials']['secret'];
        }
    }

} 