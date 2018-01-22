<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 22.01.2018
 * Time: 14:34
 */

namespace App\Store\Services;

class RetailCrmService extends AbstractService
{
    private static $instance;

    private function __construct(){}

    public static function getInstance()
    {
        if (!isset($instance)) {
            $config = require_once('../app/configs/retailcrm.php');
            $instance = new \RetailCrm\ApiClient(
                $config['url'],
                $config['api_key'],
                \RetailCrm\ApiClient::V5
            );
        }
        return $instance;
    }
}