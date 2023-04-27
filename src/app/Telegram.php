<?php

namespace App;

use Curl\Curl;

class Telegram
{
    private const TELEGRAM_API = 'https://api.telegram.org/bot';

    /**
     * @param string $botToken
     */
    public function __construct(private readonly string $botToken)
    {
    }

    private function bot(string $method, array $params = []): string|false|null
    {
        /**
         * Build url for calling api and returns response
         */

        $curl = (new Curl())->get(
            self::TELEGRAM_API . $this->botToken . "/{$method}",
            $params
        );
        if ($curl->isSuccess()) {
            return $curl->response;
        }
        return $curl->getErrorMessage();
    }

    public function sendMessage(int $chat_id, string $text): string|false|null
    {
        return $this->bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
    }

    public function getUpdates(int $offset): string|false|null
    {
        return $this->bot('getUpdates', [
            'offset' => $offset
        ]);
    }

    public function sendReport(array $errors): void
    {
        $this->sendMessage($_ENV['TELEGRAM_REPORT_CHAT_ID'], Message::make("errors.default", $errors));
    }

    public function sendButtons(int $chat_id, string $text, array $buttons) : string|false|null
    {
        $button = json_encode([
            'inline_keyboard' => [
                $buttons
            ]
        ]);
        return $this->bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $button,
            'parse_mode' => 'html'

        ]);
    }

    public function deleteMessage(int $chat_id, int $message_id) : string|false|null
    {
        return $this->bot('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
    }

}