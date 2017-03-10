<?php

Route::group(['prefix' => 'fondbot'], function () {

    Route::any('/channel/{channel}');

});