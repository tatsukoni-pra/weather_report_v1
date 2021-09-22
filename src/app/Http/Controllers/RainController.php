<?php

namespace App\Http\Controllers;

use App\Services\RainService;
use Exception;
use Illuminate\Support\Facades\Log;

class RainController extends Controller
{
    private RainService $rainService;

    public function __construct(RainService $rainService)
    {
        $this->rainService = $rainService;
    }

    public function index()
    {
        try {
            return view('rain')->with('data', $this->rainService->getRainData('139.732293,35.663613'));
        } catch (Exception $e) {
            Log::error($e);
            return view('error');
        }
    }
}
