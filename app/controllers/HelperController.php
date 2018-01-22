<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 20.01.2018
 * Time: 23:35
 */

namespace App\Store\Controllers;


class HelperController
{

    public function actionHelper(){
        require_once '../app/views/helper.php';
    }
}