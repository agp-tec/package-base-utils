<?php

Route::group(['namespace' => 'Agp\BaseUtils\Controller\Web'], function () {
    Route::get('/offline', 'IndexController@offline')->name('offline');
});

Route::group(['as' => 'web.', 'namespace' => 'Agp\BaseUtils\Controller\Web', 'middleware' => ['web']], function () {
    Route::get('push-subscription', 'PushController@subscription');
});

?>
