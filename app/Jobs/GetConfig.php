<?php

namespace App\Jobs;

use App\Config;
use App\Jobs\Count;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConfig implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // protected $cf;

    public function __construct()
    {
        //
        // $this->cf = $cf;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config            = Config::first();
        $time              = array();
        $time['start_at']  = now();
        $time['next_time'] = Carbon::now()->addHours($config['hours'])->timestamp;

        $timeStart = (int)$config['time_send'];
        if (time() < strtotime($timeStart)) {
            $timeDelay = strtotime($timeStart) - time();
        } else {
            $timeDelay = Carbon::now()->addDay()->subSecond(time() - strtotime($timeStart))->timestamp - time();
        }
        Count::dispatch($config, $time)
            ->delay(now()->addSeconds($timeDelay));
    }
}
