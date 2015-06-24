<?php
global $SETTINGS;
$SETTINGS = [
    'class' => UrbanIndo\Yii2\ElasticMemcache\Cache::class,
    'serverConfigs' => [
        [
                'host' => 'CacheClusterConfiguration1.cache.amazonaws.com', // modify this
                'port' => 11211,
                'weight' => 60,
        ],
        [
                'host' => 'CacheClusterConfiguration2.cache.amazonaws.com', // modify this
                'port' => 11211,
                'weight' => 40,
        ],
    ],
];
define('EXPECTED_NODE', 2); // what is your expected number of nodes?
