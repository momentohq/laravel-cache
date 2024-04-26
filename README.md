<img src="https://docs.momentohq.com/img/logo.svg" alt="logo" width="400"/>

[![project status](https://momentohq.github.io/standards-and-practices/badges/project-status-official.svg)](https://github.com/momentohq/standards-and-practices/blob/main/docs/momento-on-github.md)
[![project stability](https://momentohq.github.io/standards-and-practices/badges/project-stability-stable.svg)](https://github.com/momentohq/standards-and-practices/blob/main/docs/momento-on-github.md) 


## Getting Started :running:

### Requirements

- A Momento API Key is required, you can generate one using
  the [Momento Console](https://console.gomomento.com)
- A Momento cache is required, you can generate one using
  the [Momento Console](https://console.gomomento.com/create)
- At least PHP 8.0
- [Composer](https://getcomposer.org/doc/00-intro.md)
- At least [Laravel 9.x](https://laravel.com/docs/9.x/installation)
- The grpc PHP extension. See the [gRPC docs](https://github.com/grpc/grpc/blob/v1.46.3/src/php/README.md) section on
  installing the extension.

**IDE Notes**: You'll most likely want to use an IDE that supports PHP development, such
as [PhpStorm](https://www.jetbrains.com/phpstorm/) or [Microsoft Visual Studio Code](https://code.visualstudio.com/).

### Examples

Check out full working code in [the example app](https://github.com/momentohq/laravel-example)!

### Installation

Add our SDK as a dependency to your Laravel installation's `composer.json` file:

```json
{
  "require": {
    "momentohq/laravel-cache": "1.1.4"
  }
}
```

Run `composer update` to install the necessary prerequisites.

Then, add `MomentoServiceProvider` to your `config/app.php`:

```php
'providers' => [
    // ...
    Momento\Cache\MomentoServiceProvider::class
];
```

And add:

- `MOMENTO_API_KEY`=<YOUR_API_KEY>
- `MOMENTO_CACHE_NAME`=<CACHE_CREATED_ABOVE>

into your `.env` file

Finally, add the required config to your `config/cache.php`:

```php
'default' => env('CACHE_DRIVER', 'momento'),

'stores' => [
        'momento' => [
            'driver' => 'momento',
            'cache_name' => env('MOMENTO_CACHE_NAME'),
            'default_ttl' => 60,
        ],
],
```

Run `composer update` to install the necessary prerequisites.

### Usage

Check out full working code in [the example app](https://github.com/momentohq/laravel-example)!

### Tuning

Coming soon!

### Tag Support

Cache tags work the same way as explained in the Laravel [documentation](https://laravel.com/docs/9.x/cache#cache-tags).

**Notes**: If a tag may contain more than 4MB worth of keys, tagging may not work as intended.
Please contact us at support@momentohq.com or on our [Discord](https://discord.com/invite/3HkAKjUZGq) if you need
support for a larger tag set.

### Unsupported Cache Operations

The following cache operations are not supported today.
If you need these operations, please reach out to us, and we can prioritize the work to complete them.
You can file a GitHub issue, e-mail us at support@momentohq.com, or join
our [Discord](https://discord.com/invite/3HkAKjUZGq).

- `forever`

----------------------------------------------------------------------------------------
For more info, visit our website at [https://gomomento.com](https://gomomento.com)!
