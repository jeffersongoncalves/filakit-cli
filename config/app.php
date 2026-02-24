<?php

return [
    'name' => 'Filakit',
    'version' => app('git.version'),
    'env' => 'development',
    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
];
