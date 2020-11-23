<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable {

    use Queueable,
        SerializesModels;

    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function build() {
        $address = env("MAIL_FROM_ADDRESS", "janeexampexample@example.com");
        $subject = $this->data['subject'];
        $name = env("MAIL_FROM_NAME", "Jane Doe");
        return $this->view($this->data['layout'])
                        ->from($address, $name)
                        ->cc($address, $name)
                        ->bcc($address, $name)
                        ->replyTo($address, $name)
                        ->subject($subject)
                        ->with(['message' => $this->data['message']]);
    }

}
