<?php

namespace App\Commands;


use App\App;
use App\Telegram;
use Database\Entity\User;

class CommandHandler
{
    public function __construct(private CommandFactory $commandFactory, private Telegram $telegram)
    {
    }

    public function handleCommand(array $params)
    {
        $command = $this->commandFactory->createNewCommand($params['message']);
        if (!$command) {
            return;
        }
        $user = App::entityManager()->getRepository(User::class)->findOneByTgId($params['user_id']);
        if ($params['user_id'] != $params['chat_id']) {
            $this->telegram->sendMessage(
                $params['chat_id'],
                '<b>Выполение команд осуществляется только в личных сообщениях бота!😊</b>'
            );
            return;
        }
        if($params['message'] == '/start'){
            $command->execute();
            return;
        }
        if($params['message'] == '/help'){
            $command->execute();
            return;
        }

        if (!$user && $params['message'] != '/register') {
            $this->telegram->sendMessage(
                $params['chat_id'],
                '<b>Для начала вам необходимо зарегистрироваться при помощи команды /register</b>'
            );
            return;
        }

        $command->execute();
    }
}