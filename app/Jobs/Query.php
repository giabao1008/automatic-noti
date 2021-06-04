<?php

namespace App\Jobs;

use App\Jobs\MessageNotification;
use App\UserProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Query implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $offset;
    protected $limit;
    protected $config;

    public function __construct($offset, $limit, $config)
    {
        //
        $this->offset = $offset;
        $this->limit  = $limit;
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config       = $this->config;
        $limit        = $this->limit;
        $timeLoop     = $config->hours * 3600;
        $emailExcerpt = explode(',', $config->mail_excerpt);

        $timeAgo = time() - $timeLoop;
        $timeAgo = date('Y-m-d H:i:s', $timeAgo);
        $query   = UserProduct::select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
            ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
            ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
            ->whereNotNull('web_user.email')
            ->whereNotNull('web_user_last_active.last_active')
            ->where('web_user_last_active.last_active', '<', $timeAgo)
            ->whereNotIn('web_user.email', $emailExcerpt);
        $data = $query
            ->skip($this->offset)
            ->take($this->limit)
            ->get()
            ->toArray();
        $dupe = array();
        foreach ($data as $key => $value) {
            if (isset($dupe[$value["uid"]])) {
                unset($data[$key]);
                continue;
            }
            $dupe[$value["uid"]] = true;
        }

        foreach ($data as $row) {

            // Send mail
            $mailContentConfig = $config->mail_content;
            $mailContent       = str_replace('{full_name}', $row['full_name'], $mailContentConfig);

            // to $row['email]
            // set 5 min

            MessageNotification::dispatch($row['email'], $row['full_name'], $mailContent)->delay(now()->addSeconds(4));
        }

    }
}
