<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CrmSupportActivity extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $crmSupport;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($crmSupport)
    {
        $this->crmSupport = $crmSupport;
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
            'name' => $this->crmSupport->name,
            'firma' => $this->crmSupport->company,
            'phone' => $this->crmSupport->phone,
            'type' => $this->crmSupport->contact_by,
            'created_at' => $this->crmSupport->created_at->format('d/m/Y H:i'),
        ];
    }
}
