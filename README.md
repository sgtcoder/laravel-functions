# Laravel Functions #
A simple way to add functions to your laravel project.

## Installation ##

### Option 1: You can install the package via composer ###
```bash
composer require sgtcoder/laravel-functions
```

### Option 2: Add directly to your composer.json ###
```json
"require": {
    "sgtcoder/laravel-functions": "1.*"
}

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/sgtcoder/laravel-functions"
    }
]
```

### Option 3: Fork it and add to your composer.json ###
```json
"require": {
    "sgtcoder/laravel-functions": "dev-master"
}

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/<workspace>/laravel-functions"
    }
]
```

### Then Run ###
```bash
composer update
```

## Usage ##

### Call Function ###
```php
$hex = generate_random_hex();
```

### Force SSL ###
```php
# app\Http\Kernel.php => $middleware
\SgtCoder\LaravelFunctions\Middleware\HttpsProtocol::class,
```

### API Auth ###
```php
# app\Http\Kernel.php => $routeMiddleware
'auth.api' => \SgtCoder\LaravelFunctions\Middleware\AuthApi::class,
```

## Testing ##
```bash
composer test
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [sgtcoder](https://github.com/sgtcoder)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
