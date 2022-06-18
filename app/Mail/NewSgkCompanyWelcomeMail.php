<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSgkCompanyWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sgk_company, $user;


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
        return $this->markdown('emails.new_sgk_company_welcome')
            ->subject('İK Metrik Bilgilendirme')
            ->with('sgk_company', $this->sgk_company)
            ->with('user', $this->user);
    }
}
