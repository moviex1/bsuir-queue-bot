<?php

namespace App;

use App\Commands\CommandHandler;
use App\Commands\TelegramCommandFactory;

class App
{
    private static DB $entityManager;
    private CommandHandler $commandHandler;
    private TelegramCommandFactory $commandFactory;

    public function __construct(protected Config $config, protected Telegram $telegram)
    {
        static::$entityManager = new DB($config->db);
        $this->commandFactory = new TelegramCommandFactory($telegram);
        $this->commandHandler = new CommandHandler($this->commandFactory);
    }

    public static function entityManager(): DB
    {
        return static::$entityManager;
    }

    public function run()
    {
        $lastUpdateId = 0;

        while (true) {
            $updates = json_decode(
                $this->telegram->getUpdates($lastUpdateId),
                true
            );

            if (empty($updates['result'])) {
                sleep(1);
                continue;
            }

            foreach ($updates['result'] as $update) {
                $message = $update['message'] ?? null;

                if (!$message || $message['from']['is_bot']) {
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

                $this->commandFactory->setParams($params);

                $this->commandHandler->handleCommand($text);

                $lastUpdateId = $update['update_id'] + 1;
            }


            sleep(1);
        }
    }

}