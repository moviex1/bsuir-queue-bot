<?php

namespace App;

use Curl\Curl;

use Database\Models\Queue;

use Helpers\Validation;


class Telegram
{
    private $telegramApi = 'https://api.telegram.org/bot';

    public function __construct(private string $botToken)
    {
    }

    private function bot(string $method, array $params = []) : string|false|null
    {
        /**
         * Build url for calling api and returns response
         */

        $curl = (new Curl())->get(
            $this->telegramApi . $this->botToken . "/{$method}", $params
        );
        if ($curl->isSuccess()) {
            return $curl->response;
        }
        return $curl->getErrorMessage();
    }

    public function sendMessage(int $chat_id, string $text) : string|false|null
    {
        return $this->bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
    }

    public function getUpdates(int $offset) : string|false|null
    {
        return $this->bot('getUpdates', [
            'offset' => $offset
        ]);
    }

    private function queueCommand(string $message, array $params) : void
    {
        /**
         * Validates queue command. If input valid add user to queue and return message.
         * Otherwise, function sends an error message to the user.
         *
         */

        $command = explode(" ", $message, 3);
        $queue = new Queue();
        if (Validation::validateCommand($command)) {
            if (!$user = Validation::validateUser($params['user_id'])) {
                if (Validation::validatePlace($command[1])) {
                    $reserve = $queue->add([
                        'user_id' => $params['user_id'],
                        'username' => $command[2],
                        'tg_username' => $params['tg_username'],
                        'place' => $command[1]
                    ]);
                    $this->sendMessage($params['chat_id'], Message::make('queue', $reserve));
                } else {
                    $this->sendMessage($params['chat_id'], Message::make('reservedBySomeone'));
                }
            } else {
                $this->sendMessage($params['chat_id'], Message::make('reserved', $user));
            }
        } else {
            $failMessage = Validation::getFailMessage($command);
            $this->sendMessage($params['chat_id'], $failMessage);
        }
    }

    private function removeCommand(int $user_id, int $chat_id): void
    {
        /**
         * Remove user from queue
         */

        $queue = new Queue();
        $user = $queue->getById($user_id);
        $queue->remove($user_id);
        $this->sendMessage($chat_id, Message::make('remove', compact('user')));
    }


    private function listCommand(int $chat_id): void
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
        $this->sendMessage($chat_id, Message::make('list', $queue));
    }

    private function showCommand(int $user_id, int $chat_id): void
    {
        /**
         * Shows the current position of the user in the queue if he is in the queue,
         * otherwise sends him a message that he is not in the queue
         */

        $queue = new Queue();
        $user = $queue->getById($user_id);
        $this->sendMessage($chat_id, Message::make('show', compact('user')));
    }

    public function invokeCommand(string $message, array $params) : void
    {
        if (explode(" ", $message)[0] == '/queue') {
            $this->queueCommand($message, $params);
        } elseif ($message == '/remove') {
            $this->removeCommand($params['user_id'], $params['chat_id']);
        } elseif ($message == '/list') {
            $this->listCommand($params['chat_id']);
        } elseif ($message == '/show') {
            $this->showCommand($params['user_id'], $params['chat_id']);
        }
    }
    public function sendReport(string $type,\PDOException $e) : void{
        $this->sendMessage($_ENV['TELEGRAM_REPORT_CHAT_ID'], Message::make("errors.$type", ['error' => $e]));
    }

}