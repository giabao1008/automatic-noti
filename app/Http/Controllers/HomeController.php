<?php

namespace App\Http\Controllers;

use App\Carbon;
use App\Jobs\Count;

class HomeController extends Controller
{

    public function message()
    {
        // $limit  = 200;
        // $config       = Config::first();
        // $timeLoop     = $config['hours'] * 3600;
        // $emailExcerpt = explode(',', $config['mail_excerpt']);

        // $timeAgo = time() - $timeLoop;
        // $timeAgo = date('Y-m-d H:i:s', $timeAgo);
       

        // $query = UserProduct::select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
        //     ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
        //     ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
        //     ->whereNotNull('web_user.email')
        //     ->whereNotNull('web_user_last_active.last_active')
        //     ->where('web_user_last_active.last_active', '<', $timeAgo);
        // Count::dispatch()->delay(Carbon::now()->addSeconds(5));
        // $count = $query->count();
        // var_dump($count); die;
        // $data    = UserProduct::select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
        //     ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
        //     ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
        //     ->whereNotNull('web_user.email')
        //     ->whereNotNull('web_user_last_active.last_active')
        //     ->where('web_user_last_active.last_active', '<', $timeAgo)
        //     ->get()
        //     ->toArray();

        // $dupe = array();
        // foreach ($data as $key => $value) {
        //     if (isset($dupe[$value["uid"]]) || in_array($value['email'], $emailExcerpt)) {
        //         unset($data[$key]);
        //         continue;
        //     }
        //     $dupe[$value["uid"]] = true;
        // }
        // $arrEmail = array_column($data, 'email');

        // MessageNotification::dispatch('bbing1212@gmail.com', 'Nin')->delay(Carbon::now()->addMinutes(1));
    }
}
