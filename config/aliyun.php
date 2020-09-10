<?php

return [
    'accessKeyId' => env('ALIYUN_ACCESS_KEY_ID'),
    'accessKeySecret' => env('ALIYUN_ACCESS_KEY_SECRET'),
    'oss' => [
        'cdn' => env('ALIYUN_OSS_CDN'),
        'endpoint' => env('ALIYUN_OSS_ENDPOINT'),
    ],
];