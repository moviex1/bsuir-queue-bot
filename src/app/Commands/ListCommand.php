<?php

namespace App\Commands;

use App\Message;
use App\Schedule;
use Database\Models\Queue;
use Messages\Emojis;

class ListCommand extends Command
{

    public function execute(): void
    {
        /**
         * Show all the reserves.
         *
         * The Emojis array is used to randomly generate emojis for queue members
         *
         * @var array $emojis
         */
        $emojis = Emojis::getEmojis();
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