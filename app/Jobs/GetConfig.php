<?php

namespace App\Jobs;

use App\Config;
use App\UserProduct;
use App\Jobs\Count;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\DB;

class GetConfig implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $config       = Config::first();
        $timeLoop     = $config['hours'] * 3600;
        $emailExcerpt = explode(',', $config['mail_excerpt']);

        $timeAgo = time() - $timeLoop;
        $timeAgo = date('Y-m-d H:i:s', $timeAgo);

        $query = DB::table('vi_product_user_product')->select('web_user.uid AS uid', 'web_user.full_name as full_name', 'web_user.email as email', 'web_user_last_active.last_active')
            ->join('web_user', 'web_user.uid', '=', 'vi_product_user_product.uid')
            ->join('web_user_last_active', 'web_user_last_active.uid', '=', 'vi_product_user_product.uid')
            ->whereNotNull('web_user.email')
            ->whereNotNull('web_user_last_active.last_active')
            ->where('web_user_last_active.last_active', '<', $timeAgo)
            ->whereNotIn('web_user.email', $emailExcerpt);

        Count::dispatch($query, $config)
                    ->delay(now()->addMinutes(5));
    }
}
