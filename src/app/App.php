<?php

namespace App;

use App\Commands\CommandHandler;

class App
{
    private static DB $db;
    private CommandHandler $commandHandler;

    public function __construct(protected Config $config, protected Telegram $telegram)
    {
        static::$db = new DB($config->db);
        $this->commandHandler = new CommandHandler($telegram);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        $lastUpdateId = 0;

        while (true) {
            $updates = json_decode(
                $this->telegram->getUpdates($lastUpdateId),
                true
            );

            if (!empty($updates['result'])) {
                sleep(1);
                continue;
            }

            foreach ($updates['result'] as $update) {
                $message = $update['message'] ?? null;

                if (!$message || $update['message']['from']['is_bot']) {
                    $lastUpdateId = $update['update_id'] + 1;
                    continue;
                }

                $text = $message['text'] ?? null;

                if(!$text){
                    $lastUpdateId = $update['update_id'] + 1;
                    continue;
                }

                $params = [
                    'chat_id' => $message['chat']['id'],
                    'user_id' => $message['from']['id'],
                    'tg_username' => $message['from']['username'] ?? "",
                    'message' => $text
                ];

                $this->commandHandler->handleCommand($params);

                $lastUpdateId = $update['update_id'] + 1;
            }


            sleep(1);
        }
    }

}