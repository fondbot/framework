<?php

/** @var Route $router */
use Illuminate\Support\Facades\Route;

$router->group(
    ['namespace' => 'FondBot\Controllers', 'prefix' => 'fondbot'],
    function () use (&$router) {
        $router->get('/', 'IndexController@show');

        collect(['GET', 'POST', 'PATCH', 'DELETE'])->each(function (string $method) use (&$router) {
            $router->$method('/{channel}', 'ChannelController@webhook');
        });
    });
