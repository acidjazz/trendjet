<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Provider;

use Illuminate\Http\Request;
use App\Notifications\UserAttempt;
use Illuminate\Support\Facades\Auth; 

use Socialite;

class AuthController extends \acidjazz\metapi\MetApiController
{

  public function sessions() {
    return $this->render(Auth::user()->sessions);
  }

  public function redirect(Request $request, $provider)
  {

    if (!in_array($provider, Provider::$allowed)) {
      return $this->error('auth.provider.allowed', 'Auth Provider is not allowed');
    }

    return Socialite::driver($provider)->redirect();

  }

  public function callback(Request $request, String $provider)
  {
    if (!in_array($provider, Provider::$allowed)) {
      return $this->error('auth.provider.allowed', 'Auth Provider is not allowed');
    }

    $oaUser = Socialite::driver($provider)->stateless()->user();

    $user = User::where('email', $oaUser->email)->first();

    if ($user == null) {
      $user = new User([
        'name' => $oaUser->name,
        'email' => $oaUser->email,
        'avatar' => $oaUser->avatar_original ?? $oaUser->avatar,
      ]);
      if (!$user->save()) {
        return $this->error('auth.user_save', 'Error saving user');
      }
    }

    if ($user == null || !in_array($provider, $user->providers->pluck('name')->toArray())) {
      $provider = new Provider([
        'user_id' => $user->id,
        'name' => $provider,
        'avatar' => $oaUser->avatar_original ?? $oaUser->avatar,
        'payload' => $request->get('state'),
      ]);
      if (!$provider->save()) {
        return $this->error('auth.provider_save', 'Error saving provider');
      }

      if ($user->avatar == null) {
        $user->avatar = $provider->avatar;
        $user->save();
      }
    }

    Auth::login($user);

    return response(
      view('complete', [
        'json' => json_encode([
          'provider' => $provider,
          'token' => Auth::token(),
          'user' => Auth::user(),
          'to' => Auth::session()->to,
        ])
      ]))->cookie('token', Auth::token(), 0, '', config('app.domain'));

  }

  public function attempt(Request $request)
  {

    $this->option('email', 'required|email');
    $this->option('to', 'string');

    if (!$this->verify()) {
      return $this->error();
    }

    $user = User::where('email', $request->email)->first();

    $attempt = Auth::attempt($user);

    $user->notify(new UserAttempt($attempt));

    return $this->render(['cookie' => $attempt->cookie]);

  }

  public function login(Request $request)
  {
    $this->option('token', 'required|alpha_num|size:64');
    $this->option('cookie', 'required|alpha_num|size:64');

    if (!$this->verify()) {
      return $this->error();
    }

    if (Auth::user() != null) {
      return $this->error('auth.already');
    }

    if (!$login = Auth::verify($request->token, $request->cookie)) {
      return $this->error('auth.invalid');
    }

    return $this->render([
        'token' => Auth::token(),
        'user' => Auth::user(),
        'to' => Auth::session()->to,
      ])->cookie('token', Auth::token(), 0, '', config('app.domain'));
  }

  public function loginAs(Request $request, String $email)
  {

    Auth::check();
    
    if (($user = User::where('email', $email)->first()) == null) {
      return $this->error('notfound', 'E-mail address not found');
    }

    Auth::login($user);

    return $this->render([
      'token' => Auth::token(),
      'user' => Auth::user(),
    ])->cookie('token', Auth::token(), 0, '', config('app.domain'), false, false);
  }

  public function me(Request $request)
  {
    return $this->render(Auth::user());
  }

  public function logout(Request $request)
  {
    return $this->render(Auth::logout())->cookie('token', false, 0, '', config('app.domain'));
  }
}
