<?php

namespace Mikrahub\LaravelSettings\Contracts;

interface SettingsStorage
{
    public function get(string $key, $default = null);

    public function set(string $key, $value): void;

    public function forget(string $key): void;

    public function has(string $key): bool;
    public function load();
    public function all(): array;
}
