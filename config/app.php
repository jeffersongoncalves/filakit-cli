<?php

use App\Providers\AppServiceProvider;

return [
    'name' => 'Filakit',
    'version' => app('git.version'),
    'env' => 'development',
    'providers' => [
        AppServiceProvider::class,
    ],
];
