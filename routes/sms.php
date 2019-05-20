<?php

$router = resolve('missive');

$router->register('LOG {message}', function (string $path, array $values) {
    \Log::info($values['message']);
});
