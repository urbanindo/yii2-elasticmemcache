# yii2-elasticmemcache
Yii2 component for AWS Elasticache Memcache without node discovery plugin

[![Latest Stable Version](https://poser.pugx.org/urbanindo/yii2-elasticmemcache/v/stable.svg)](https://packagist.org/packages/urbanindo/yii2-elasticmemcache)
[![Total Downloads](https://poser.pugx.org/urbanindo/yii2-elasticmemcache/downloads.svg)](https://packagist.org/packages/urbanindo/yii2-elasticmemcache)
[![Latest Unstable Version](https://poser.pugx.org/urbanindo/yii2-elasticmemcache/v/unstable.svg)](https://packagist.org/packages/urbanindo/yii2-elasticmemcache)
[![Build Status](https://travis-ci.org/urbanindo/yii2-elasticmemcache.svg)](https://travis-ci.org/urbanindo/yii2-elasticmemcache)

# Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist urbanindo/yii2-elasticmemcache "*"
```

or add

```
"urbanindo/yii2-elasticmemcache": "*"
```

to the require section of your `composer.json` file.

# Usage

```php
'components' => [
    'cache' => [
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
	        ]
	    ],
	    'cache' => [
	    	'class' => yii\caching\FileCache::class
	    ]
    ]
]
```

# Running Test
- copy test/template-local.php to local.php
- modify it to your aws elasticache configuration
- run `vendor/bin/phpunit --bootstrap test/bootstrap.php test/CacheTest`
