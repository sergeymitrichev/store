<?php

/**
 * @const bool   DEVELOP  флаг рабочей среды
 */
define('DEVELOP', true);
/**
 * определяет путь к папке, где лежит проект (папки:  config, controllers, models, views)
 */
define("STORE", realpath(__DIR__ . '/../app') . '/');
$loader = require_once __DIR__ . '/../vendor/autoload.php'; //Файл загрузчик модулей приложения
/**
 * Запуск приложения
 *
 *
 */

$router = Bit55\Litero\Router::fromGlobals();
$router->add('/module/action', function () {
    echo 'Hello from Litero!';
});
$router->add([

    '/users/create:any'       => 'App\Store\Controllers\UsersController@actionCreate',
    '/users/get:any'          => 'App\Store\Controllers\UsersController@actionGet',
    '/users/update:any'       => 'App\Store\Controllers\UsersController@actionUpdate',
    '/users/remove:any'       => 'App\Store\Controllers\UsersController@actionRemove',

    '/helper'       => 'App\Store\Controllers\HelperController@actionHelper',
]);

$params = array();
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET": {
        $params['get'] = $_GET;
        break;
    }
    case "POST": {
        $params['post'] = $_POST;
    }
}


if ($router->isFound()) {
    $router->executeHandler(
        $router->getRequestHandler(),
        $params
    );
}
else {
    // Simple "Not found" handler
    $router->executeHandler(function () {
        http_response_code(404);
        echo '404 Not found';
    }, array());
}
