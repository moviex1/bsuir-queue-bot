<?php

namespace Database\Migrations;

use App\App;
use PDO;
use PDOException;

class RecommendationMigration
{
    private $table = "recommendations";

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
                              `user_id` INT UNSIGNED NOT NULL , 
                              `grade` TINYINT UNSIGNED NULL DEFAULT NULL , 
                              `recommendation` TEXT NULL DEFAULT NULL , 
                              `lesson_id` INT UNSIGNED NOT NULL , 
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