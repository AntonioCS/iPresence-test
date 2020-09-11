<?php

namespace App\Service\Quotes;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

abstract class AbstractDataProvider implements DataProviderInterface
{

    private ?AdapterInterface $cache;
    private int $defaultExpireTimeInSecs = 3600;

    public function __construct(?AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function fetch(string $key, int $limit): array
    {
        $cacheKey = $this->cacheKey($key, $limit);
        return $this->cacheGet($cacheKey, function() use ($key, $limit) {
            return $this->internalFetch($key, $limit);
        });
    }

    abstract protected function internalFetch(string $key, int $limit) : array;

    protected function getDefaultExpireTimeInSecs(): int
    {
        return $this->defaultExpireTimeInSecs;
    }

    protected function setDefaultExpireTimeInSecs(int $defaultExpireTimeInSecs): void
    {
        $this->defaultExpireTimeInSecs = $defaultExpireTimeInSecs;
    }

    protected function cacheKey(...$keyParts) : string {
        $key = "";

        foreach ($keyParts as $k) {
            $key .= $k;
        }

        return \str_replace(' ', '-', $key);
    }

    protected function cacheGet(string $key, callable $func) {
        if ($this->cache) {

            $value = $this->cache->get($key, function (ItemInterface $item) use ($func) {
                $item->expiresAfter($this->getDefaultExpireTimeInSecs());
                return $func();
            });

            return $value;
        }

        return $func();
    }
}
