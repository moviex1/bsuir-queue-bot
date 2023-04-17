<?php

namespace Helpers;

use App\App;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;

class Validation
{

    public static function validateNum(string $num): bool
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

}
