<?php

namespace App\Commands;

use App\Telegram;

class CommandHandler
{
    private array $queueMessages = [];

    public function __construct(private Telegram $telegram)
    {
    }

    private function checkQueue($message, $params)
    {
        foreach ($this->queueMessages as $key => $queueMessage) {
            if ($key == $params['chat_id']) {
                if ($queueMessage['state'] == 'waitForRecommendation') {
                    $this->telegram->sendMessage($params['user_id'], 'Ваша рекомендация:' . $message);
                    unset($this->queueMessages[$key]);
                } elseif (is_numeric($message)) {
                    $this->telegram->sendMessage($params['user_id'], 'Вы поставили оценку:' . $message);
                    $this->queueMessages[$key]['state'] = 'waitForRecommendation';
                    $this->telegram->sendMessage($params['user_id'], 'Напишите рекомендацию...');
                } else {
                    $this->telegram->sendMessage($params['user_id'], 'Введите число от 1 до 10');
                }
                return true;
            }
        }
        return false;
    }

    private function getCommandFiles($str)
    {
        $command = explode('C', $str);
        return count($command) > 1 && strlen($command[0]) > 1;
    }

    public function handleCommand(array $params)
    {
        $files = scandir(__DIR__);
        $commands = array_map(fn($str) => '/' . strtolower(explode('C', $str)[0]),
            array_filter($files, fn($a) => $this->getCommandFiles($a)));
        if (in_array($params['message'], $commands)) {
            $classname = __NAMESPACE__ . '\\' . ucfirst(explode('/', $params['message'])[1]) . 'Command';
            $class = new $classname($this->telegram, $params);
            $class->execute();
        }
    }
}