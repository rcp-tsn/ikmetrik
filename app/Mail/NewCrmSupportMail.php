<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCrmSupportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $crmSupport;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($crmSupport)
    {
        $this->crmSupport = $crmSupport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->crmSupport->contact_by == 'DEMO') {
            return $this->markdown('emails.new_crm_demo_support_to_us')
                ->subject('Yeni Demo İsteği')
                ->with('crmSupport', $this->crmSupport);
        } else {
            return $this->markdown('emails.new_crm_support_to_us')
                ->subject('Yeni Destek Talebi')
                ->with('crmSupport', $this->crmSupport);
        }

    }
}
