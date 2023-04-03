<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/error_handler.php';

use App\App;
use App\Config;
use Database\Migrations\QueueMigration;
use App\Telegram;
use Symfony\Component\Dotenv\Dotenv;

const MESSAGE_PATH = __DIR__ . '/../messages';

$env = new Dotenv();
$env->load(__DIR__ . '/../../.env');

$telegram = new Telegram($_ENV['BOT_TOKEN']);

$config = new Config($_ENV);
$app  = new App($config,$telegram);
$migration = new QueueMigration();


$migration->migrate();

$app->run();

//$offset = 0;
//while (1) {
//    $updates = json_decode($telegram->getUpdates($offset), true)['result'];
//    foreach ($updates as $update) {
//        if (array_key_exists('callback_query', $update)) {
//            $telegram->deleteMessage(453730244, $update['callback_query']['message']['message_id']);
//            $telegram->sendMessage(453730244, $update['callback_query']['data']);
//        }
//        if (array_key_exists('message', $update)) {
//            if (array_key_exists('text', $update['message'])) {
//                if($update['message']['text'] == '/button'){
//                    $telegram->sendButton(453730244);
//                }
//            }
//        }
//        $offset = $update['update_id']+1;
//    }
//
//    sleep(1);
//}