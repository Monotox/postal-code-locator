<?php

namespace App\Utils;

use DateInterval;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CacheUtils extends AbstractController
{
    public function __construct(CacheItemPoolInterface $cache) {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     */
    public function get($key)
    {
        return $this->cache->getItem($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|\DateInterval $lifetime
     * @return bool
     */
    public function save($key, $value, $lifetime = 0)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter(new DateInterval($lifetime));
        return $this->cache->save($item);
    }
}
