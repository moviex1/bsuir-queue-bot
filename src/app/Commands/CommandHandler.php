<?php

namespace App\Commands;


use App\App;
use App\Telegram;
use Database\Entity\User;

class CommandHandler
{
    private array $queueMessages = [];

    public function __construct(private CommandFactory $commandFactory, private Telegram $telegram)
    {
    }

    public function handleCommand(array $params)
    {
        $command = $this->commandFactory->createNewCommand($params['message']);
        if(!$command){
            return;
        }
        $user = App::entityManager()->getRepository(User::class)->findOneByTgId($params['user_id']);
        if(!$user && $params['message'] != '/register'){
            $this->telegram->sendMessage($params['chat_id'], 'You need to register first');
            return;
        }
        $command->execute();
    }
}