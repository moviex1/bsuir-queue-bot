<?php

namespace App;

class App
{
    private static DB $db;

    public function __construct(protected Config $config, protected Telegram $telegram)
    {
        static::$db = new DB($config->db);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        $lastUpdateId = 0;

        while (true) {
            $updates = json_decode(
                $this->telegram->getUpdates($lastUpdateId),
                true
            );

            if (!empty($updates['result'])) {
                foreach ($updates['result'] as $update) {
                    if (!array_key_exists('edited_message', $update)) {
                        if (!$update['message']['from']['is_bot']) {
                            $params = [
                                'chat_id' => $update['message']['chat']['id'],
                                'user_id' => $update['message']['from']['id'],
                                'tg_username' => $update['message']['from']['username'],
                            ];
                            $this->telegram->invokeCommand($update['message']['text'], $params);
                        }
                    }
                    $lastUpdateId = $update['update_id'] + 1;
                }
            }
            sleep(1);
        }
    }

}