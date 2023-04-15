<?php

namespace Helpers;

use App\App;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;

class Validation
{

    private static function validateNum(string $num): bool
    {
        if (is_numeric($num)) {
            return is_int(+$num) && (+$num) > 0 && (+$num) <= 30;
        }
        return false;
    }

    public static function validateUsername(string $username): bool
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


    public static function validatePlace(int $place, DateTime $date, $group): bool
    {

        $queue = App::entityManager()->getRepository(Queue::class)->findByPlace;
        return !$queue->wherePlace($place);
    }

}
