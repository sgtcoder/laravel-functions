# Titanium6 Laravel Functions #

A simple way to add functions to your laravel project.

## Install ##
- Add to your composer.json
```
"require": {
    "titanium-6/laravel-functions": "1.*"
}

"repositories": [
    {
        "name": "titanium-6/laravel-functions",
        "type": "vcs",
        "url": "git@bitbucket.org:titanium-6/laravel-functions.git"
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