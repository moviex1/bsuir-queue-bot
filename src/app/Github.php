<?php

namespace App;

use Curl\Curl;

class Github
{
    private static string $api = 'https://api.github.com/';
    private static string $owner = 'moviex1';
    private static string $repo = 'bsuir-queue-bot';

    private static function queryBuilder(string $path, array $params = []): string|bool|null
    {
        $curl = (new Curl())->get(
            self::$api . "{$path}",
            $params
        );
        if ($curl->isSuccess()) {
            return $curl->response;
        }
        return $curl->getErrorMessage();
    }

    public static function getStaredUsers(): array
    {
        $path = 'repos/' . self::$owner . '/' . self::$repo . '/stargazers';
        $response = self::queryBuilder($path);
        $stargazers = json_decode($response, true);
        $usernames = [];
        foreach ($stargazers as $stargazer){
            $usernames[] = $stargazer['login'];
        }
        return $usernames;
    }

}