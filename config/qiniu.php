<?php

return [
    'access_key' => env('QINIU_ACCESS_KEY'),
    'secret_key' => env('QINIU_SECRET_KEY'),
    'buckets' => [
        env('QINIU_BUCKET_PLUBLIC') => [
            'domain' => env('QINIU_BUCKET_PLUBLIC_DOMAIN'),
            'visibility' => 'public',
        ],
        env('QINIU_BUCKET_PRIVATE') => [
            'domain' => env('QINIU_BUCKET_PRIVATE_DOMAIN'),
            'visibility' => 'private',
        ]
    ]
];
