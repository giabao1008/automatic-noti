<?php

namespace App\Http\Controllers;

use App\Config;
use App\UserProduct;

class HomeController extends Controller
{

    public function message()
    {
        // $limit  = 200;
        $config       = Config::first();
        $timeLoop     = $config['hours'] * 3600;
        $emailExcerpt = explode(',', $config['mail_excerpt']);

        $timeAgo = time() - $timeLoop;
        $timeAgo = date('Y-m-d H:i:s', $timeAgo);
        $data    = UserProduct::select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
            ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
            ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
            ->whereNotNull('web_user.email')
            ->whereNotNull('web_user_last_active.last_active')
            ->where('web_user_last_active.last_active', '<', $timeAgo)
            ->get()
            ->toArray();
        // so sánh kiểu gì ta

        $dupe = array();
        foreach ($data as $key => $value) {
            if (isset($dupe[$value["uid"]]) || in_array($value['email'], $emailExcerpt)) {
                unset($data[$key]);
                continue;
            }
            $dupe[$value["uid"]] = true;
        }
        // $arrEmail = array_column($data, 'email');

        $email    = new \SendGrid\Mail\Mail();
        $email->setFrom("info@toliha.edu.vn", "TOLIHA");
        $email->setSubject("Nhắc nhở học tập");
        $email->addTo("giabao1008@gmail.com", "Tuyền");
        $email->addContent(
            "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
