# Laravel Settings Package

A flexible Laravel package to manage settings globally or for individual models. This package allows you to store, retrieve, cache, and manage settings across your application, either globally or per model.

## Features

- Store global settings across your application.
- Attach settings to any Eloquent model with the `HasSettings` trait.
- Support for multiple storage mechanisms (e.g., MySQL, Redis, remote services).
- Optional caching for enhanced performance.
- Easy-to-use API with the `Settings` facade.
- Multi tenancy support
---

## Installation

To install the package, follow these steps:

### Step 1: Install via Composer

Run the following command to install the package:

```bash
composer require mikrahub/laravel-settings
```

### Step 2:  Run migrations

```bash
php artisan migrate
```

### Step 3:  Use it

```php
//store values
Settings::set('app.theme', 'dark');

//fetch values
$value = Settings::get('app.theme', 'default');

//remove it
Settings::forget('app.theme');
```

### Step 3.1: Use it with models
To use settings with an Eloquent model, simply add the HasSettings trait to your model.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mikrahub\LaravelSettings\Traits\HasSettings;

class User extends Model
{
    use HasSettings;
}
//--------------------------------------------------------------------------

$user = User::find(1);
$user->settings->set('notifications_enabled', true);

$notificationsEnabled = $user->settings->get('notifications_enabled', false);
```
### Configure
Publish the configuration file, which allows you to customize storage options, caching, and other settings:
```bash
php artisan vendor:publish --provider="Mikrahub\LaravelSettings\SettingsServiceProvider" --tag="config"
```
