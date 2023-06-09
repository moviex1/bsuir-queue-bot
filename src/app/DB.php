<?php

namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;

/**
 * @mixin EntityManager
 */

class DB
{
    private EntityManager $entityManager;

    /**
     * @param array $config
     * @throws MissingMappingDriverImplementation
     */
    public function __construct(array $config)
    {
        try {
            $connection = DriverManager::getConnection($config);
            $ORMConfig = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../Database/Entity'], true);
            $this->entityManager = new EntityManager($connection, $ORMConfig);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->entityManager, $name], $arguments);
    }
}