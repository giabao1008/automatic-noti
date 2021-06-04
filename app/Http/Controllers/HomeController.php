<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Config;
use App\UserProduct;
use App\Jobs\GetConfig;


class HomeController extends Controller
{

    public function message()
    {   
        GetConfig::dispatch()->delay(now()->addSeconds(1));

    }
}
