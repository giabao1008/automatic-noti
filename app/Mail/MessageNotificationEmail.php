<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class MessageNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     
    public $full_name;

    public function __construct($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {       
        Log::channel('daily')->info("aloooo");

        return $this->from('info@toliha.edu.vn', 'TOLIHA')->view('mail.message_notification')->subject('Nhắc nhở học tập');
    }
}
