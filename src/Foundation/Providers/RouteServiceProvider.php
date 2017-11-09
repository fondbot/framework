<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected $namespace = 'FondBot\Foundation';

    public function map(): void
    {
        Route::get('/', 'FondBot\Foundation\Controller@index');

        Route::group(['middleware' => 'fondbot.webhook'], function () {
            Route::get('webhook/{channel}/{secret?}', 'FondBot\Foundation\Controller@webhook')->name('fondbot.webhook');
            Route::post('webhook/{channel}/{secret?}', 'FondBot\Foundation\Controller@webhook')->name('fondbot.webhook');
        });
    }
}
