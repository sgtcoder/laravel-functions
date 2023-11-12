<?php

namespace SgtCoder\LaravelFunctions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SgtCoder\LaravelFunctions\LaravelFunctions
 */
class LaravelFunctions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SgtCoder\LaravelFunctions\LaravelFunctions::class;
    }
}
