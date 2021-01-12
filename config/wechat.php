<?php

return [
    'web' => [
        'app_id' => env('WECHAT_WEB_APPID'),
        'secret' => env('WECHAT_WEB_SECRET'),
        'oauth' => [
            'scopes'   => ['snsapi_login'],
        ],
        'redirect_uri' => env('WECHAT_WEB_REDIRECT_URI')
    ]
];
