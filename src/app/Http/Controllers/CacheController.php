<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CacheController extends Controller
{
    public function index()
    {
        $redis = Redis::connection('default');
        $redis->set('hoge', 'tatsu');
        Log::info($redis->get('hoge'));
        return 'hello!';
    }
}
