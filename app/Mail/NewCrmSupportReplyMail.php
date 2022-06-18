<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCrmSupportReplyMail extends Mailable
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
        $result = '';
        $reply = '';
        $a = explode('[cevap]', $this->crmSupport->message);
        if (count($a) > 1) {
            if (strlen($a[1]) > 0) {
                $result = $a[1];
                $reply = $a[0];
            }
        }

        return $this->markdown('emails.new_crm_support_reply')
            ->subject('Destek Talebiniz Cevaplandı')
            ->with('crmSupport', $this->crmSupport)
            ->with('result', $result)
            ->with('reply', $reply);

    }
}
