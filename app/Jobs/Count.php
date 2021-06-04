<?php

namespace App\Jobs;

use App\Jobs\Query;
use App\UserProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Count implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $query;
    protected $limit;
    protected $config;

    public function __construct($config)
    {
        $this->limit  = 200;
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config    = $this->config;
        $limit     = $this->limit;
        $timeLoop     = $config->hours* 3600;
        $emailExcerpt = explode(',', $config->mail_excerpt);

        $timeAgo = time() - $timeLoop;
        $timeAgo = date('Y-m-d H:i:s', $timeAgo);
        $query = UserProduct::select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
            ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
            ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
            ->whereNotNull('web_user.email')
            ->whereNotNull('web_user_last_active.last_active')
            ->where('web_user_last_active.last_active', '<', $timeAgo)
            ->whereNotIn('web_user.email', $emailExcerpt);
        
        $total     = $query->count();
        $totalTurn = ceil($total / $limit);

        // Tính toán xem với số lượng mail như này thì để delay bao nhiêu là hợp lí
        $maxTimeCanSend = $config->hours - 1; // cứ trừ 1 tiếng cho an toàn :v
        $maxMinutes     = $maxTimeCanSend * 60;
        $config->delay  = ceil($maxMinutes / $total);

        for ($i = 0; $i < $totalTurn; $i++) {
            $offset = $i * $limit;

            Query::dispatch($offset, $limit, $config)
                ->delay(now()->addSeconds(5));
        }

    }
}
