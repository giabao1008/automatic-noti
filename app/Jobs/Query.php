<?php

namespace App\Jobs;

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
    
    protected $query;
    protected $offset;
    protected $limit;
    protected $config;

    public function __construct($query, $offset, $limit; $config)
    {
        //
        $this->query = $query;
        $this->offset = $offset;
        $this->limit = $limit;
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
        $data = $this->query
                ->skip($this->offset)
                ->take($this->limit)
                ->get()
                ->toArray();
        foreach($data as $row) {

            // Send mail
            $mailContentConfig = $config['mail_content'];
            $mailContent  = str_replace('{full_name}', $row['full_name'], $mailContentConfig);
            
            // to $row['email]
            // set 5 min

            MessageNotification::dispatch($row['email', $row['full_name'], $mailContent])->delay(Carbon::now()->addMinutes($config['delay']));
        }

    }
}
