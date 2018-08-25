<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Login;
use Auth;


class AuthController extends \acidjazz\metapi\MetApiController
{

  public function attempt(Request $request)
  {

    $this->option('email', 'required|email');
    $this->option('to', 'string');

    if (!$this->verify()) {
      return $this->error();
    }

    $user = User::where('email', $request->email)->first();

    return $this->render(Login::attempt(
      $user, 
      $request->to, 
      $request->ip(),
      $request->header('User-Agent'))
    );

  }

  public function login(Request $request)
  {
    $this->option('id', 'required|alpha_num|size:64');
    $this->option('cookie', 'required|alpha_num|size:64');

    if (!$this->verify()) {
      return $this->error();
    }

    if (Auth::user() !== null) {
      return $this->error('auth.already');
    }

    if (!$login = Login::verify($request->id, $request->cookie)) {
      return $this->error('auth.invalid');
    }

    Auth::login(User::find($login->user_id));
    $to = $login->to;

    return $this->render([
        'token' => $request->session()->getId(),
        'user' => Auth::user(),
        'to' => $to,
      ])->cookie('token', $request->session()->getId(), 0, null, config('app.domain'));
  }

  public function loginAs(Request $request, String $email)
  {

    Auth::login(User::where('email', $email)->first());

    return $this->render(['user' => Auth::user(), 'token' => $request->session()->getId()])
                ->cookie('token', $request->session()->getId(), 0, null, config('app.domain'));

  }

  public function me(Request $request)
  {

    if (Auth::check()) {
      return $this->render(Auth::user());
    }

    return $this->render(false);
  }

  public function logout(Request $request)
  {
    return $this->render(Auth::logout());
  }
}
