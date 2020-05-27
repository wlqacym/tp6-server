<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'default' => env('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        'redis'   =>  [
            'type'     => 'redis',
            'host'     => env('cache.host', '127.0.0.1'),
            'port'     => env('cache.port', 6379),
            'password' => env('cache.password', ''),
            'prefix'   => env('cache.prefix', ''),
            'expire'   => 0,
        ],
        // session 储存
        'sess'   =>  [
            'type'     => 'redis',
            'host'     => env('cache.host', '127.0.0.1'),
            'port'     => env('cache.port', 6379),
            'password' => env('cache.password', ''),
            'prefix'   => env('cache.sess', 'sess_'),
            'expire'   => 0,
        ],
        // 更多的缓存连接
    ],
];
