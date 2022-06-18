<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserMail extends Mailable
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
        if (config('app.demo_company_id') == $this->company->id) {
            return $this->markdown('emails.new_user')
                ->subject('Demo Giriş Bilgileriniz')
                ->with('company', $this->company)
                ->with('user', $this->user)
                ->with('password', $this->password);
        } else {
            return $this->markdown('emails.new_user')
                ->subject('Üyelik Giriş Bilgileriniz')
                ->with('company', $this->company)
                ->with('user', $this->user)
                ->with('password', $this->password);
        }

    }
}
