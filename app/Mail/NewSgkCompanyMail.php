<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSgkCompanyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sgk_company;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sgk_company)
    {
        $this->sgk_company = $sgk_company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new_sgk_company')
            ->subject('Yeni Şube Girişi')
            ->with('sgk_company', $this->sgk_company);
    }
}
