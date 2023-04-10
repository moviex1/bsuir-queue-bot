<?php

namespace App;

class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'dbname' => $env['DB_DATABASE'],
                'user'     => $env['DB_USER'],
                'password'     => $env['DB_PASS'],
                'host'     => $env['DB_HOST'],
                'driver'   => $env['DB_DRIVER'] ?? 'pdo_mysql',
            ],
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}