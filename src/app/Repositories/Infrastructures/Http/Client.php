<?php

namespace App\Repositories\Infrastructures\Http;

use Illuminate\Support\Facades\Http;

class Client
{
    /**
     * @param string $uri
     * @param int $timeout = 10
     * @param int $retry = 5
     * @param int $retryWaitTime = 100
     * @return mixed
     */
    public function get(
        string $uri,
        int $timeout = 10,
        int $retry = 10,
        int $retryWaitTime = 500
    ) {
        return Http::timeout($timeout)
            ->retry($retry, $retryWaitTime)
            ->get($uri);
    }
}
