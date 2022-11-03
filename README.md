<head>
  <meta name="Momento Laravel cache driver" content="Taggable Momento serverless cache driver for Laravel">
</head>
<img src="https://docs.momentohq.com/img/logo.svg" alt="logo" width="400"/>

[![project status](https://momentohq.github.io/standards-and-practices/badges/project-status-incubating.svg)](https://github.com/momentohq/standards-and-practices/blob/main/docs/momento-on-github.md)
[![project stability](https://momentohq.github.io/standards-and-practices/badges/project-stability-alpha.svg)](https://github.com/momentohq/standards-and-practices/blob/main/docs/momento-on-github.md)

# Momento Laravel Cache Driver

:warning: Alpha SDK :warning:

This is an official Momento package, but the API is in an alpha stage and may be subject to backward-incompatible
changes. For more info, click on the alpha badge above.

Momento Serverless Cache driver for Laravel: a fast, simple, pay-as-you-go caching solution without
any of the operational overhead required by traditional caching solutions!

## Getting Started :running:

### Requirements

- A Momento Auth Token is required, you can generate one using
  the [Momento CLI](https://github.com/momentohq/momento-cli)
- At least PHP 8.0
- Laravel 9.x
- The grpc PHP extension. See the [gRPC docs](https://github.com/grpc/grpc/blob/v1.46.3/src/php/README.md) section on
  installing the extension.

**IDE Notes**: You'll most likely want to use an IDE that supports PHP development, such
as [PhpStorm](https://www.jetbrains.com/phpstorm/) or [Microsoft Visual Studio Code](https://code.visualstudio.com/).

### Installation

Install composer [as described on the composer website](https://getcomposer.org/doc/00-intro.md).

Add our repository to your `composer.json` file and our package as a dependency:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/momentohq/laravel-cache"
    }
  ],
  "require": {
    "momentohq/laravel-cache": "0.0.1"
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

Finally, add the required config to your `config/cache.php`:

```php
'default' => env('CACHE_DRIVER', 'momento'),

'stores' => [
        'momento' => [
            'driver' => 'momento',
            'cache_name' => env('MOMENTO_CACHE_NAME'),
            'default_ttl' => 60,
        ],
		// ...
],
```

----------------------------------------------------------------------------------------
For more info, visit our website at [https://gomomento.com](https://gomomento.com)!