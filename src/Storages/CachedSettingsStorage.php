<?php

namespace Mikrahub\LaravelSettings\Storages;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mikrahub\LaravelSettings\Contracts\SettingsStorage;

class CachedSettingsStorage implements SettingsStorage
{
    protected $store;
    protected $cacheTTL;
    protected array $data = [];

    public function __construct(SettingsStorage $store, $cacheTTL = 3600)
    {
        $this->store = $store;
        $this->cacheTTL = $cacheTTL;
        $this->data = Cache::get($this->getCacheKey());
    }

    public function get(string $key, $default = null)
    {
        if (empty($this->data)) {
            $this->refreshCache();
        }

        return Arr::get($this->data, $key, $default);
    }

    protected function refreshCache()
    {
        $cacheKey = $this->getCacheKey();
        $this->data = $this->store->all();
        Cache::put($cacheKey, $this->data, $this->cacheTTL);
    }

    protected function getCacheKey(): string
    {
        return sprintf(
            '%s%s%s%s',
            'laravel-settings',
            $this->store->getTenantId(),
            $this->store->getModel()?->getMorphClass() ?? '-',
            $this->store->getModel()?->getKey() ?? '-'
        );
    }

    public function all(): array
    {
        if (empty($this->data)) {
            $this->refreshCache();
        }

        return $this->data;
    }

    public function set(string $key, $value): void
    {
        $this->store->set($key, $value);
        $this->refreshCache();
    }

    public function forget(string $key): void
    {
        $this->store->forget($key);
        $this->refreshCache();
    }

    public function has(string $key): bool
    {
        if (empty($this->data)) {
            $this->refreshCache();
        }

        return Arr::has($this->data, $key);
    }

    public function load()
    {
        return $this->store->load();
    }
}
