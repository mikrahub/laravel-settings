<?php

namespace Mikrahub\LaravelSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static get($key, $default)
 * @method static set($key, $value)
 * @method static has($key)
 * @method static forget($key)
 * @method static all()
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-settings';
    }
}
