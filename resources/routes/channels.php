<?php

Route::group(['prefix' => 'fondbot', 'namespace' => 'FondBot\Http\Controllers'], function () {

    Route::any('/{channel}', [
        'as' => 'fondbot.webhook',
        'uses' => 'WebhookController@handle',
    ]);

});