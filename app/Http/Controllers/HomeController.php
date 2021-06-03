<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\GetConfig;

class HomeController extends Controller
{

    public function message()
    {
        GetConfig::dispatch()->delay(Carbon::now()->addMinutes(1));
    }
}
