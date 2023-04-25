<?php

namespace App;

use App\Commands\CommandHandler;
use App\Commands\TelegramCommandFactory;
use App\States\StateHandler;
use App\States\StateManager;
use Database\Entity\User;

class App
{
    private static DB $entityManager;
    private CommandHandler $commandHandler;
    private TelegramCommandFactory $commandFactory;
    private StateManager $stateManager;
    private StateHandler $stateHandler;

    public function __construct(private Config $config, private Telegram $telegram)
    {
        static::$entityManager = new DB($config->db);
        $this->stateManager = new StateManager();
        $this->stateHandler = new StateHandler($this->stateManager);
        $this->commandFactory = new TelegramCommandFactory($telegram, $this->stateManager);
        $this->commandHandler = new CommandHandler($this->commandFactory, $this->telegram);
    }

    public static function entityManager(): DB
    {
        return static::$entityManager;
    }


    public function run()
    {
        $lastUpdateId = 0;

        while (true) {
            $this->processUpdates($lastUpdateId);
            sleep(1);
        }
    }

    private function processUpdates(int &$lastUpdatedId)
    {
        $updates = $this->getUpdates($lastUpdatedId);
        foreach ($updates as $update) {
            $this->processUpdate($update);
            $lastUpdatedId = $update['update_id'] + 1;
        }
    }

    private function getUpdates(int $lastUpdatedId)
    {
        $updates = json_decode(
            $this->telegram->getUpdates($lastUpdatedId),
            true
        );

        $result = $updates['result'];

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    private function processUpdate(array $update)
    {
        $message = $update['message'] ?? null;
        $message = $message ?? $update['callback_query']['message'];
        if (!$message) {
            return;
        }

        $text = $message['text'] ?? null;

        if (!$text) {
            return;
        }

        $params = $this->getCommandParams($message, $text, $update, $this->isCallbackQuery($update));


        if (!$this->stateHandler->hasState($params) && !$this->isCancelCommand($message['text'])) {
            if ($params['user_id'] != $params['chat_id']) {
                return;
            }
            $this->stateHandler->handleInput($params);
            return;
        };

        $this->commandFactory->setParams($params);

        $this->commandHandler->handleCommand($params);
    }

    private function isCallbackQuery(array $update): bool
    {
        return array_key_exists('callback_query', $update);
    }

    private function getCommandParams(array $message, string $text, array $update, bool $callback = false): array
    {
        if ($callback) {
            $callbackQuery = $update['callback_query'];
            return [
                'chat_id' => $message['chat']['id'],
                'message_id' => $message['message_id'],
                'user_id' => $callbackQuery['from']['id'],
                'tg_username' => $callbackQuery['from']['username'] ?? "",
                'message' => $text,
                'callback_data' => $callbackQuery['data']
            ];
        } else {
            return [
                'chat_id' => $message['chat']['id'],
                'message_id' => $message['message_id'],
                'user_id' => $message['from']['id'],
                'tg_username' => $message['from']['username'] ?? "",
                'message' => $text,
                'callback_data' => null
            ];
        }
    }

    private function isCancelCommand(string $message)
    {
        return explode("@BsuirQueueBot", $message)[0] == '/cancel';
    }

}