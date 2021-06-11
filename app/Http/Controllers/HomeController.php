<?php

namespace App\Http\Controllers;

use App\Jobs\GetConfig;

class HomeController extends Controller
{

    public function message()
    {
        // dd(Config::first());
        GetConfig::dispatch()->delay(now()->addSeconds(1));
    }
}
