<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/* cd C:\xampp\htdocs\LumennameParsing3
   php -S localhost:8000 -t public
   C:\xampp\htdocs\LumennameParsing3>php -S localhost:8000 -t public

   vendor\bin\phpunit --coverage-html report/
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('inputFullname','nameParserController@inputFullname');
$router->post('showPayload','nameParserController@showPayload');
