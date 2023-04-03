<?php

namespace Database\Models;

use App\App;
use App\DB;
use App\Schedule;
use App\Telegram;
use PDO;
use PDOException;

/**
 * Queue model to work with db table 'queue'
 */

class Queue implements Model
{
    private DB $db;
    public function __construct()
    {
        $this->db = App::db();
    }

    public function getById($id) : array | false
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM queue WHERE user_id=:user_id AND date=:date');
            $stmt->execute([
                'user_id' => $id,
                'date' => Schedule::getNextLesson('250701')['date']
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error($e);
            return [];
        }
    }


    public function add($params) : ?array
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO queue (user_id,username,date, tg_username, place, emoji) VALUES (:user_id,:username, :date, :tg_username, :place, :emoji)');
            $stmt->execute([
                'user_id' => $params['user_id'],
                'username' => $params['username'],
                'date' => Schedule::getNextLesson('250701')['date'],
                'tg_username' => $params['tg_username'],
                'place' => $params['place'],
                'emoji' => rand(1,112)
            ]);
            return [
                'date' => Schedule::getNextLesson('250701')['date'],
                'place' => $params['place']
            ];
        } catch (PDOException $e) {
            trigger_error($e);
            return [];
        }
    }



    public function remove($id) : void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM queue WHERE user_id=:user_id AND date=:date');
            $stmt->execute([
                'user_id' => $id,
                'date' => Schedule::getNextLesson('250701')['date']
            ]);
        } catch (PDOException $e) {
            trigger_error($e);
        }
    }

    public function getAll() : array | false
    {
        try{
            $stmt = $this->db->prepare('SELECT * FROM queue WHERE date=:date');
            $stmt->execute([
                'date' => Schedule::getNextLesson('250701')['date']
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            trigger_error($e);
            return [];
        }
    }

    public function wherePlace(int $place) : array | false | null
    {
        /**
         * Returns a user who is in a certain place, or returns false if no one is taking up place
         */

        try{
            $stmt = $this->db->prepare('SELECT * FROM queue WHERE date=:date AND place=:place');
            $lesson = Schedule::getNextLesson('250701');
            $stmt->execute([
                'date' => $lesson['date'],
                'place' => $place
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            trigger_error($e);
            return null;
        }
    }
}