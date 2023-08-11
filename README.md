# Laravel Functions #

A simple way to add functions to your laravel project.

## Install Options ##
### Option 1: Add directly to your composer.json ###
```
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

### Option 2: Fork it and add to your composer.json ###
```
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
```
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