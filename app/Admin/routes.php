<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/boxes_generate', 'BoxGenerateController@index');
    $router->post('/generate', 'BoxGenerateController@generate')->name('generate');
    $router->get('/box_download/{box}', 'BoxGenerateController@download')->name('download');
    $router->resource('/boxes', 'BoxController');
});
