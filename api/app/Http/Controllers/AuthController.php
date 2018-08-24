<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Login;

class AuthController extends \acidjazz\metapi\MetApiController
{

  public function attempt(Request $request)
  {

    $this->option('email', 'required|email');
    $this->option('to', 'string');

    if (!$this->verify()) {
      return $this->error();
    }

    return $this->render($bob);

    $user = User::where('email', $request->email)->first();

    return $this->render(Login::attempt(
      $user, 
      $request->to, 
      $request->ip(),
      $request->header('User-Agent'))
    );

  }
}
