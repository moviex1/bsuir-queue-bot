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
