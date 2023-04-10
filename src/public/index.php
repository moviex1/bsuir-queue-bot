<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/error_handler.php';

use App\App;
use App\Config;
use App\Telegram;
use Database\Entities\Recommendation;
use Database\Entities\User;
use Symfony\Component\Dotenv\Dotenv;

const MESSAGE_PATH = __DIR__ . '/../messages';

$env = new Dotenv();
$env->load(__DIR__ . '/../../.env');


$telegram = new Telegram($_ENV['BOT_TOKEN']);
$config = new Config($_ENV);
$app  = new App($config,$telegram);


$student = App::entityManager()->getRepository(User::class)->findOneBy(['name' => 'Nikita']);

foreach ($student->getReceivedRecommendations()->toArray() as $recommendation){
    echo $recommendation->getRecommendation() . PHP_EOL;
}


//$app->run();
