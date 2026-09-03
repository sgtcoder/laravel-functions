<?php

/**
 * Registers the package's runtime macros so PHPStan can resolve them through Larastan's
 * macro reflection, rather than every call site being suppressed as an undefined method.
 */

require_once __DIR__ . '/vendor/autoload.php';

SgtCoder\LaravelFunctions\Providers\CustomServiceProvider::registerMacros();
