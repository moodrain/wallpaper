<?php

namespace App\Jobs;

use App\Mail\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    private $mail;
    private $mailer;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
        $this->queue = 'mail';
    }


    public function handle()
    {
        \Illuminate\Support\Facades\Mail::mailer($this->mail->mailer)->send($this->mail);
    }

}
