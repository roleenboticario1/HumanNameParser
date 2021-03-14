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

//Total Count of Entry and Exit
$router->post('get-total-count','TransactionController@getTotalCount');
$router->post('get-total-count-by-nationality','TransactionController@getTotalCountByNationality');
$router->post('get-total-count-of-travels-port-of-entry','TransactionController@getTotalCountOfTravelsPortOfEntry');
$router->post('get-total-count-of-travels-port-of-exit','TransactionController@getTotalCountOfTravelsPortOfExit');

//Total Count of Entry and Exit With Gender
$router->post('get-total-count-by-gender','TransactionController@getTotalCountByGender');

//ENTRY_PORT BY NATIONALITY
$router->post('get-total-count-entry-port-by-nationality','TransactionController@getTotalCountEntryPortByNationality');
// EXIT_PORT BY NATIONALITY
$router->post('get-total-count-exit-port-by-nationality','TransactionController@getTotalCountExitPortByNationality');

//BOTH ENTRY_PORT AND EXIT_PORT BY NATIONALITY
$router->post('get-total-count-enrty-exit-port-by-nationality','TransactionController@getTotalCountEntryPortAndExitPortByNationality');
