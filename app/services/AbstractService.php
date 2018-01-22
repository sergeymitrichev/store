<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 22.01.2018
 * Time: 14:49
 */

namespace App\Store\Services;


abstract class AbstractService
{
    const UAPI      = 'UApi';
    const RETAILCRM = 'RtailCrm';
    const SENDPULSE = 'SendPulse';

    public static function initial($service)
    {
        $service .= 'Service';
        return $service->getInstance();
    }
    abstract public static function getInstance();
}