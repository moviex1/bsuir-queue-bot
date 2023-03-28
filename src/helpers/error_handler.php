<?php

use App\Telegram;

function errorHandler($errno, $errstr, $errfile, $errline){
    $errors = [
        'error_code' => $errno,
        'error_message' => $errstr,
        'error_file' => $errfile,
        'error_line' => $errline
    ];
    $telegram = new Telegram($_ENV['BOT_TOKEN']);
    $telegram->sendReport($errors);
}

set_error_handler('errorHandler');
