<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDemoUserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $company;
    protected $user;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $user, $password)
    {
        $this->company = $company;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new_demo_user')
            ->subject('Demo Giriş Bilgileriniz')
            ->with('company', $this->company)
            ->with('user', $this->user)
            ->with('password', $this->password);
    }
}
