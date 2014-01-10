<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 1:34 PM
 */

namespace Venturocket\Api;


use Exception;

class ApiException extends \Exception{
    public function __construct($status, $error){
        parent::__construct("{$status} - {$error}");
    }
}