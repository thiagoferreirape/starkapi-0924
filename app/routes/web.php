<?php



/** @var \Laravel\Lumen\Routing\Router $router */


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->get('balance', 'BalanceController@getBalance');

$router->get('transfer', 'TransferController@transfer');
$router->get('transfersList', 'TransferController@transfersList');
$router->get('transferById/{id}', 'TransferController@transferById');
$router->get('transfer/pdf/{id}', 'TransferController@getPDF');
$router->get('transferHistory/{id}','TransferController@transferHistory');
$router->post('monitor', 'BaseController@monitor');


$router->get('invoice', 'InvoiceController@invoice');
$router->get('invoiceById', 'InvoiceController@invoiceById');
$router->get('invoicesList', 'InvoiceController@invoicesList');
$router->get('invoice/qr/{id}', 'InvoiceController@invoiceQR');


$router->get('boleto', 'BoletoController@boleto');
$router->get('boletoById', 'BoletoController@boletoById');
$router->get('boleto/pdf/{id}', 'BoletoController@boletoPDF');

$router->get('handleBucket', 'BaseController@handleBucket');

$router->get('registerHook','BaseController@registerHook');


