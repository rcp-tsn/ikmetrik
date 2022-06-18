<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SgkCompanyActivity extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $sgk_company;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sgk_company)
    {
        $this->sgk_company = $sgk_company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return [

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'sgk_company_id' => $this->sgk_company->id,
            'sgk_company_name' => $this->sgk_company->name,
            'created_date' => $this->sgk_company->created_at->format('d/m/Y H:i'),
        ];
    }
}
