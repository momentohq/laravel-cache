{{ ossHeader }}

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

### Examples

Check out full working code in [the example app](https://github.com/momentohq/laravel-example)!

### Installation

Install composer [as described on the composer website](https://getcomposer.org/doc/00-intro.md).

Add our repository to your `composer.json` file and our SDK as a dependency:

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

Run `composer update` to install the necessary prerequisites.

### Usage

Check out full working code in [the example app](https://github.com/momentohq/laravel-example)!

### Error Handling

Coming soon!

### Tuning

Coming soon!

### Tag Support

Cache tags work the same way as explained in the Laravel [documentation](https://laravel.com/docs/9.x/cache#cache-tags).

**Notes**: If a tag may contain more than 4MB worth of keys, tagging may not work as intended.
Please contact us at support@momentohq.com or on our [Discord](https://discord.com/invite/3HkAKjUZGq) if you need
support for a larger tag set.

### Unsupported Cache Operations

The following cache operations are not supported today:

- `many`
- `putMany`
- `increment`
- `decrement`
- `forever`
- `flush`

{{ ossFooter }}
