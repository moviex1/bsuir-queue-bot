<?php

namespace App\Commands;

use App\Telegram;

class CommandHandler
{
    private array $queueMessages = [];

    public function __construct(private CommandFactory $commandFactory)
    {
    }

    private function checkQueue($params)
    {
        return array_key_exists($params['user_id'],$this->queueMessages);
    }

    public function handleCommand(string $message)
    {
        $command = $this->commandFactory->createNewCommand($message);
        $command->execute();
    }
}