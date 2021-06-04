<?php

namespace App\Jobs;

use App\Config;
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
        $config = Config::first();

        Count::dispatch($config)
                    ->delay(now()->addSeconds(5));
    }
}
