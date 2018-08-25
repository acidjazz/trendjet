<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Login;
use Illuminate\Support\Facades\Auth; 

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

    if (Auth::user() != null) {
      return $this->error('auth.already');
    }

    if (!$login = Login::verify($request->id, $request->cookie)) {
      return $this->error('auth.invalid');
    }

    $user = User::find($login->user_id);
    Auth::login($user);
    $token = $user->createToken('trendjet')->accessToken;

    return $this->render([
        'token' => $token,
        'user' => Auth::user(),
        'to' => $login->to,
      ])->cookie('token', $token, 0, '', config('app.domain'));
  }

  public function loginAs(Request $request, String $email)
  {
    $user = User::where('email', $email)->first();
    Auth::login($user);
    $token = $user->createToken('trendjet')->accessToken;

    return $this->render([
      'token' => $token,
      'user' => Auth::user(),
    ])->cookie('token', $token, 0, '', config('app.domain'), false, false);
  }

  public function me(Request $request)
  {
    return $this->render(Auth::guard('api')->user());
  }

  public function logout(Request $request)
  {
    return $this->render(Auth::guard('api')->user()->token()->revoke());
  }
}
