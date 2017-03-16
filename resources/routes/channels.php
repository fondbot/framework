<?php

Route::any('{channel}', [
    'as' => 'fondbot.webhook',
    'uses' => 'WebhookController@handle',
]);

Route::any('{channel}/verify', [
    'as' => 'fondbot.webhook.verification',
    'uses' => 'VerificationController@handle',
]);
