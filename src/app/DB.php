<?php

namespace App;

use PDO;
use PDOException;

/**
 * @mixin PDO
 */

class DB
{
    private PDO $pdo;

    public function __construct($config)
    {
        $defaultOptions = [
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $this->pdo = new PDO(
                $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['user'],
                $config['pass'],
                $config['options'] ?? $defaultOptions
            );
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}