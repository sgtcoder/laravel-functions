<?php

// Load ServiceHelpers first to ensure service functions are available
require_once __DIR__ . '/Helpers/ServiceHelpers.php';

// Load remaining helper files
array_map(
    fn($file) => $file !== __FILE__ && !in_array(basename($file), ['ServiceHelpers.php']) ? require_once $file : null,
    glob(__DIR__ . '/Helpers/*.php')
);
