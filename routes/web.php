<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/seed/deputados', 'SeedController@seedDeputados');

$app->get('/seed/despesas', 'SeedController@seedDespesas');

$app->group(['prefix' => 'deputados'], function () use ($app) {
    $app->get('', 'DeputadoController@list');
    $app->get('/{id}', 'DeputadoController@get');
    $app->get('/byCodigoApi/{codigo}', 'DeputadoController@getByCodigoApi');
});

$app->group(['prefix' => 'tiposDespesa'], function () use ($app) {
    $app->get('', 'TipoDespesaController@list');
});

$app->group(['prefix' => 'fornecedores'], function () use ($app) {
    $app->get('', 'FornecedorController@list');
    $app->get('/{id}', 'FornecedorController@get');
    $app->get('/byCpfCnpj/{cpfCnpj}', 'FornecedorController@findByCpfCnpj');
});
