<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index()
    {
        Log::error('error test local!');
        throw new \Exception('テストlocal');
        return 'hello!';
    }
}
