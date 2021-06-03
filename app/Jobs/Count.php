<?php

namespace App\Jobs;

use App\UserProduct;
use App\Jobs\Query;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Bus\Dispatcher;

class Count implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    // Time to try a job
    public $tries = 3;

    // Set timeout of a job
    public $timeout = 60;

    protected $query;
    protected $limit;
    protected $config;

    public function __construct($query, $config)
    {
        $this->query = $query;
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
        $config = $this->config;
        $limit = $this->limit;
        $total = $this->query->count();
		$totalTurn = ceil($total/$limit);


        // Tính toán xem với số lượng mail như này thì để delay bao nhiêu là hợp lí
        $maxTimeCanSend = $config['hours'] - 1; // cứ trừ 1 tiếng cho an toàn :v
        $maxMinutes     = $maxTimeCanSend*60;
        $config['delay'] = ceil($maxMinutes/$total);

		for($i = 0; $i < $totalTurn; $i++){
            $offset = $i*$limit;
            $job = (new Query($query, $offset, $limit, $config)->delay(now()->addSeconds(5));
            app(Dispatcher::class)->dispatch($job);
		}

    }
}
