<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserLogin extends Notification
{
  use Queueable;

  private $login;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($login)
  {
    $this->login = $login;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
      return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Login Attempt')
      ->markdown('emails.user.login', $this->toArray($notifiable));
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
      'id' => $this->login->id,
      'cookie' => $this->login->cookie,
      'created_at' => $this->login->created_at,
    ];
  }
}
