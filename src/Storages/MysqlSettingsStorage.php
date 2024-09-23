<?php

namespace Mikrahub\LaravelSettings\Storages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mikrahub\LaravelSettings\Contracts\SettingsStorage;

class MysqlSettingsStorage implements SettingsStorage
{
    protected array $data = [];
    private $tableName = 'laravel_settings';
    private bool $loaded = false;
    private ?Model $model = null;
    private $tenantId;

    public function load(): void
    {
        $q = $this->filterQuery();

        try {
            $settings = $q->firstOrFail();
            $this->data = json_decode($settings->data, true);
            $this->loaded = true;
        } catch (RecordNotFoundException $e) {
            $this->data = [];
        }
    }

    private function filterQuery(): Builder
    {
        $q = DB::connection('mysql')
            ->table($this->tableName)
            ->where('tenant_id', $this->tenantId);
        if ($this->model) {
            $q->where('scope_type', $this->model->getMorphClass())
                ->where('scope_id', $this->model->getKey());
        } else {
            $q->whereNull('scope_type')->whereNull('scope_id');
        }

        return $q;
    }

    public function get(string $key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function set(string $key, $value): void
    {
        Arr::set($this->data, $key, $value);

        $q = $this->filterQuery();

        if ($this->loaded) {
            $q->update(['data' => json_encode($this->data)]);
        } else {
            $q->insert([
                'tenant_id' => $this->tenantId,
                'scope_type' => $this->model->getMorphClass(),
                'scope_id' => $this->model->getKey(),
                'data' => json_encode($this->data),
            ]);
            $this->loaded = true;
        }
    }

    public function forget(string $key): void
    {
        Arr::forget($this->data, $key);
        if ($this->loaded) {
            $this->filterQuery()->update(['data' => json_encode($this->data)]);
        }
    }

    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * @param mixed $tenantId
     */
    public function setTenantId($tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }
}
