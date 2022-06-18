<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CanStartUsingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $company;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sgk_company, $user)
    {
        $this->sgk_company = $sgk_company;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.can_start_using')
            ->subject('İK Metrik Kullanıma Hazır')
            ->with('sgk_company', $this->sgk_company)
            ->with('user', $this->user);
    }
}
