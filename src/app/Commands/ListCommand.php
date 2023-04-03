<?php

namespace App\Commands;

use App\Message;
use App\Schedule;
use App\Telegram;
use Database\Models\Queue;

class ListCommand implements Command
{
    public function __construct(private Telegram $telegram,private array $params)
    {
    }

    public function execute(): void
    {
        /**
         * Show all the reserves.
         *
         * The Emojis array is used to randomly generate emojis for queue members
         *
         * @var array $emojis
         */
        include MESSAGE_PATH . '/emojis.php';
        $queue = new Queue();
        $reserves = $queue->getAll();
        usort($reserves, fn($a, $b) => $a['place'] - $b['place']);

        $lesson = Schedule::getNextLesson('250701');
        $queue = [
            'reserves' => $reserves,
            'date' => $lesson['date'],
            'emojis' => $emojis,
        ];
        $this->telegram->sendMessage($this->params['chat_id'], Message::make('list', $queue));
    }
}