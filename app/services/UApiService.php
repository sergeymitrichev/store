<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 22.01.2018
 * Time: 22:15
 */

namespace App\Store\Services;


class UApiService extends AbstractService
{
    private static $instance;

    private function __construct(){}

    public static function getInstance()
    {
        if (!isset($instance)) {
            $config = require_once('../app/configs/uapi.php');
            $instance = new \APIuCoz\ApiClient(
                $config['site'],
                $config['consumer_key'],
                $config['consumer_secret'],
                $config['token'],
                $config['token_secret'],
                \APIuCoz\ApiClient::V1
            );
        }
        return $instance;
    }
}