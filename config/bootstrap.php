<?php

namespace Config;

require_once  __DIR__ . "/../vendor/autoload.php";


use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;


$env = new Dotenv();
$env->load(__DIR__ . '/../.env');


$paths = [__DIR__ . '/../src/database/Entities'];
$isDevMode = true;

$dbParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'dbname'   => $_ENV['DB_DATABASE'],
    'host' => $_ENV['DB_HOST']
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);