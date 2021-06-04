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
    
    protected $receiver_email, $full_name, $content;

    public function __construct($receiver_email, $full_name, $content)
    {
        $this->receiver_email = $receiver_email;
        $this->full_name      = $full_name;
        $this->content = $content;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        
        $email    = new \SendGrid\Mail\Mail();
        $email->setFrom("info@toliha.edu.vn", "TOLIHA");
        $email->setSubject("Nhắc nhở học tập");
        $email->addTo("giabao1008@gmail.com", $this->full_name);
        $email->addContent(
            "text/html", $this->content
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            Log::channel('daily')->info($errors->getMessage() . ' - ' . $errors->getFile() . ' - ' . $errors->getLine() . "\r\n");
        }

        // sleep(3);
    }
}
