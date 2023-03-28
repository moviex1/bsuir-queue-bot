<?php

namespace Helpers;

use Database\Models\Queue;

class Validation
{

    private static function validateNum(string $num): bool
    {
        if (is_numeric($num)) {
            return is_int(+$num) && (+$num) > 0 && (+$num) <= 30;
        }
        return false;
    }

    private static function validateUsername(string $username): bool
    {
        return (strlen($username) < 50);
    }

    public static function validateCommand(array $command): bool
    {
        if (count($command) < 3) {
            return false;
        }
        return self::validateUsername($command[2]) && self::validateNum($command[1]);
    }

    public static function getFailMessage(array $command): string
    {
        $message = "";
        if (count($command) < 3) {
            return "<b>Вы не передали место или имя. <i>(/queue [место] [имя])</i></b>";
        }
        $message .= !self::validateNum(
            $command[1]
        ) ? "<b>Неверно введеное место.<i>(число от 1 до 30)</i></b>" . PHP_EOL : "";
        $message .= !self::validateUsername(
            $command[2]
        ) ? "<b>Слишком длинное имя.<i>(длина не более 30 символов)</i></b>" : "";
        return strlen($message) > 1 ? $message :"<b>Ошибка при вводе данных</b>";
    }

    public static function validatePlace(int $place) : bool{
       $queue = new Queue();
       return !$queue->wherePlace($place);
    }
    public static function validateUser(int $user_id){
        $queue = new Queue();
        return $queue->getById($user_id);
    }


}
