<?php

namespace Mikrahub\LaravelSettings;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Mikrahub\LaravelSettings\Contracts\SettingsStorage;
use Mikrahub\LaravelSettings\Exceptions\UnexpectedDriverException;
use Mikrahub\LaravelSettings\Storages\CachedSettingsStorage;

class SettingStorageFactory
{
    public static function create(?string $tenantId = null, ?Model $model = null): SettingsStorage
    {
        $storageDriver = Config::get("laravel-settings.storage");

        /**
         * @var SettingsStorage $store
         */
        try {
            $store = App::make("laravel-settings.{$storageDriver}");
        } catch (BindingResolutionException $e) {
            throw new UnexpectedDriverException(
                sprintf('Please check your configuration. Add you %s driver to provider', $storageDriver)
            );
        }
        $store->setTenantId($tenantId ?? Config::get('laravel-settings.default_tenant_id', '0'));

        if ($model !== null) {
            $store->setModel($model);
        }

        $store->load();

        if (Config::get('laravel-settings.cache_enabled', false)) {
            $store = new CachedSettingsStorage($store, Config::get('laravel-settings.cache_duration', 60));
        }

        return $store;
    }
}
