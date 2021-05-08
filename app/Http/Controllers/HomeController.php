<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Jobs\MessageNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function message() {
        $customers = User::all();

        foreach ($customers as $customer) {
            $last_time = $customer->created_at;

            if($last_time) {
                if(strtotime(date("Y-m-d", strtotime($last_time)) . " +3 day") <= strtotime('now')) {
                    // Send notification email
                    MessageNotification::dispatch($customer->email);
                }
            }
        }
    }
}
