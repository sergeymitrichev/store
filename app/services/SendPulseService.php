<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 22.01.2018
 * Time: 22:19
 */

namespace App\Store\Services;


class SendPulseService extends AbstractService
{
    private static $instance;

    private function __construct(){}

    public static function getInstance()
    {
        if (!isset($instance)) {
            $config = require_once('../app/configs/sendpulse.php');
            $instance = new \Sendpulse\RestApi\ApiClient(
                $config['api_user_id'],
                $config['api_secret'],
                new \Sendpulse\RestApi\Storage\FileStorage()
            );
        }
        return $instance;
    }
}