<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable
{
    use Queueable, SerializesModels;


    public $content;

    public function content($content = null)
    {
        if ($content) {
            $this->content = $content;
            return $this;
        }
        return $this->content;
    }

    public function build()
    {
        return $this->html($this->content);
    }

}
