<?php

namespace Database\Migrations;

use App\App;
use PDO;
use PDOException;

class UsersMigration implements Migration
{
    private string $table = "users";

    private function checkIfExist() : ?bool
    {
        try {
            $db = App::db();
            $stmt = $db->prepare('SHOW TABLES LIKE :table;');
            $stmt->execute([
                'table' => $this->table
            ]);
            return !empty($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            return null;
        }
    }

    public function migrate()
    {
        $db = App::db();
        if (!$this->checkIfExist()) {
            try {
                $db->query(
                    "CREATE TABLE `$_ENV[DB_DATABASE]`.`$this->table` (
                              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
                              `tg_id` BIGINT UNSIGNED NOT NULL , 
                              `tg_username` VARCHAR(255) NULL DEFAULT NULL , 
                              `username` VARCHAR(100) NULL , 
                              `group` MEDIUMINT UNSIGNED NOT NULL , 
                              PRIMARY KEY (`id`)
                              ) ENGINE = InnoDB;"
                );
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
}