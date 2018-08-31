<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserAttempt extends Notification
{
  use Queueable;

  private $attempt;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($attempt)
  {
    $this->attempt = $attempt;
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
      'token' => $this->attempt->token,
      'cookie' => $this->attempt->cookie,
      'created_at' => $this->attempt->created_at,
    ];
  }
}
