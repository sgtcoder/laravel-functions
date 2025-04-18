<?php

array_map(
    fn($file) => $file !== __FILE__ ? require_once $file : null,
    glob(__DIR__ . '/Helpers/*.php')
);
