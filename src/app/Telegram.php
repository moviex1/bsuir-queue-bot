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

    public function sendReport(array $errors) : void{
        $this->sendMessage($_ENV['TELEGRAM_REPORT_CHAT_ID'], Message::make("errors.default", $errors));
    }

}