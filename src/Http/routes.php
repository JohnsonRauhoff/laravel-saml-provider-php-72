<?php

Route::get(
    'logout',
    [
        'as' => 'logout',
        'uses' => 'SamlController@logout',
    ]
);

Route::get(
    'login',
    [
        'as' => 'login',
        'uses' => 'SamlController@login',
    ]
);

Route::get(
    config('saml.metadataUrl', 'metadata'),
    [
        'as' => 'metadata',
        'uses' => 'SamlController@metadata',
    ]
);

Route::post(
    config('saml.acsUrl', 'acs'),
    [
        'as' => 'acs',
        'uses' => 'SamlController@acs',
    ]
);

Route::get(
    config('saml.slsUrl', 'sls'),
    [
        'as' => 'sls',
        'uses' => 'SamlController@sls',
    ]
);