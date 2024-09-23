<?php

namespace Mikrahub\LaravelSettings\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Mikrahub\LaravelSettings\Contracts\SettingsStorage;
use Mikrahub\LaravelSettings\SettingStorageFactory;

/**
 * @property SettingsStorage $settings
 */
trait HasSettings
{
    protected ?SettingsStorage $settingsInstance = null;

    public function getSettingsStore(): SettingsStorage
    {
        if (!$this->settingsInstance) {
            $this->settingsInstance = SettingStorageFactory::create(model:$this);
        }

        return $this->settingsInstance;
    }

    protected function settings(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getSettingsStore(),
           );
    }
}
