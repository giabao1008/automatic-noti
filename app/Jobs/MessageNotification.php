<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\MessageNotificationEmail;

class MessageNotification implements ShouldQueue
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
    
    protected $receiver_email;

    public function __construct($receiver_email)
    {
        $this->receiver_email = $receiver_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->receiver_email)->send(new MessageNotificationEmail());            
        } catch (\Exception $errors) {
            Log::channel('daily')->info($errors->getMessage() . ' - ' . $errors->getFile() . ' - ' . $errors->getLine() . "\r\n");
        }

        sleep(3);
    }
}
