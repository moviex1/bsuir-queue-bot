<?php

namespace Database\Migrations;

use App\App;
use PDO;
use PDOException;

/**
 * This class has methods to check if table exist
 * and if not - creates new table
 */

class QueueMigration implements Migration
{
    private string $table = "queue";

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
                    "CREATE TABLE `$_ENV[DB_DATABASE]`.`$this->table`
                (`id` INT NOT NULL AUTO_INCREMENT ,
                 `user_id` INT NOT NULL ,
                 `username` VARCHAR(30) NOT NULL ,
                 `reserved_at` TIMESTAMP NOT NULL ,
                 `date` TIMESTAMP NULL,
                 `tg_username` VARCHAR(40) NULL, 
                 `place` INT NOT NULL,
                 `emoji` INT NOT NULL DEFAULT '1',     
                  PRIMARY KEY (`id`))
                  ENGINE = InnoDB;"
                );
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }

}