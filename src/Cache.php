<?php

/**
 * Cache class file.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */
namespace UrbanIndo\Yii2\ElasticMemcache;

/**
 * Cache extends MemCache ability to discovery memcache nodes for S3 ElastiCache.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */
class Cache extends \yii\caching\MemCache
{
    /**
     * Cache component to cache the node discovery.
     *
     * @var \yii\caching\Cache
     */
    private $_cache = null;

    /**
     * List of configuration nodes.
     *
     * @var array
     */
    private $_serverConfigs = [];

    /**
     * The number of seconds to cache the discovered nodes configuration.
     *
     * @var int
     */
    public $cacheTime = 60;

    /**
     * Initialize the component.
     */
    public function init()
    {
        $this->setServers($this->loadNodesConfigurations());
        parent::init();
    }

    /**
     * @param mixed $config
     */
    public function setCache($config)
    {
        if ($config) {
            $this->_cache = \Yii::createObject($config);
        }
    }

    /**
     * @return \yii\caching\Cache
     */
    public function getCache()
    {
        return $this->_cache;
    }

    public function setServerConfigs($configs)
    {
        foreach ($configs as $config) {
            if (!$config['host']) {
                throw new \Exception('Host configuration need to be set.');
            }
        }
        $this->_serverConfigs = $configs;
    }

    /**
     * @return array
     */
    public function getServerConfigs()
    {
        return $this->_serverConfigs;
    }

    protected function loadNodesConfigurations()
    {
        try {
            if (($cacheable = null != $this->getCache())) {
                $cachedConfig = $this->getCache()->get('clusters');
            }
            if (!$cacheable || !$cachedConfig) {
                $servers = $this->getServerConfigs();
                $cachedConfig = [];
                foreach ($servers as $server) {
                    $config = $this->getClusterConfig($server['host'], $server['port'], $server);
                    $cachedConfig = array_merge($cachedConfig, $config);
                }
                if ($cacheable) {
                    $this->getCache()->set('clusters', $cachedConfig, $this->cacheTime);
                }
            }

            return $cachedConfig;
        } catch (\Exception $exc) {
            \Yii::warning("Unable to retrieve cluster configuration because of {$exc->getMessage()}. Defaults to server configuration");

            return $this->getServerConfigs();
        }
    }

    /**
     * Returns cluster configurations for a config node.
     *
     * @param string $hostname the hostname of config node.
     * @param int    $port     port
     *
     * @return array of host and port.
     */
    protected function getClusterConfig($hostname, $port, $template)
    {
        $fp = fsockopen($hostname, $port);
        fwrite($fp, "config get cluster\r\n");
        $raw = '';
        while (substr($raw, -5, 3) !== 'END') {
            $raw .= fgets($fp, 1024);
        }
        fclose($fp);

        return array_map(function ($configline) use ($template) {
            list($host, , $port) = explode('|', $configline);

            return array_merge($template, [
                'host' => $host,
                'port' => $port,
            ]);
        }, preg_split("/\s+/", explode("\n", $raw)[2]));
    }
}
