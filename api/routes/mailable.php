<?php
/**
 * Short description for mailable.php
 *
 * @package mailable
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

use \Illuminate\Mail\Markdown;

use App\Models\User;
use App\Notifications\UserLogin;

Route::get('/mailable/user-login', function() {
  $user = User::inRandomOrder()->first();
  $message = (new UserLogin($user))->toMail($user);
  return app(Markdown::class)->render($message->markdown, $message->data());
});
