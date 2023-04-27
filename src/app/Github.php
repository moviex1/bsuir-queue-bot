<?php

namespace App;

use Curl\Curl;

class Github
{
    private const GITHUB_API = 'https://api.github.com/';

    public function __construct(private readonly string $owner, private readonly string $repository)
    {
    }

    public function getStaredUsers(): array
    {
        $path = 'repos/' . $this->owner . '/' . $this->repository . '/stargazers';
        $response = $this->queryBuilder($path);
        $stargazers = json_decode($response, true);
        return array_map(fn(array $stargazer) => $stargazer['login'], $stargazers);
    }

    /**
     * @param string $path
     * @param array $params
     * @return string|bool|null
     */
    private function queryBuilder(string $path, array $params = []): string|bool|null
    {
        $curl = (new Curl())->get(
            self::GITHUB_API . "{$path}",
            $params
        );

        return $curl->isSuccess() ? $curl->response : $curl->getErrorMessage();
    }


}