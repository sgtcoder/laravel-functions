# Titanium6 Laravel Functions #

A simple way to add functions to your laravel project.

## Install ##
- Add to your composer.json
```
"require": {
    "sgtcoder/laravel-functions": "1.*"
}

"repositories": [
    {
        "name": "sgtcoder/laravel-functions",
        "type": "vcs",
        "url": "https://github.com/sgtcoder/laravel-functions.git"
    }
]
```
- Then Run
```
composer update
```

## Usage ##

- Call Function
```php
$hex = generate_random_hex();
```