<?php

Route::any('{channel}', [
    'as' => 'fondbot.webhook',
    'uses' => 'WebhookController@handle',
]);
