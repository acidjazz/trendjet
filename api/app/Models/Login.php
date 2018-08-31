<?php

namespace App\Models;

use User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{

  protected $fillable = ['id', 'user_id', 'cookie', 'to', 'ip', 'agent', 'source'];

  public $incrementing = false;

  public static function make($user, $source)
  {

    $login = self::create([
      'id' => hash('sha256', mt_rand()),
      'user_id' => $user->id,
      'source' => $source,
      'cookie' => hash('sha256', mt_rand()),
    ]);

    return $login;
  }

  public static function attempt($user, $to, $ip, $agent) {

    if ($user === null) {
      return hash('sha256', mt_rand());
    }

    // delete all previous attempts of logging in
    self::where(['user_id' => $user->id, 'verified' => false])->delete();

    $login = self::create([
      'id' => hash('sha256', mt_rand()),
      'user_id' => $user->id,
      'source' => 'attempt',
      'cookie' => hash('sha256', mt_rand()),
      'ip' => $ip,
      'agent' => $agent,
      'to' => $to,

    ]);


    return $login->cookie;

  }

  public static function verify($id, $cookie)
  {

    $login = self::where([
      'id' => $id, 
      'verified' => false
    ])->whereIn('source', ['invited','approved'])->first(); 

    if ($login !== null) {
      $login->verified = true;
      $login->save();
      return $login;
    }

    $login = self::where([
      'id' => $id, 
      'cookie' => $cookie, 
      'verified' => false
    ])->first();

    if ($login === null) {
      return false;
    }

    if ($login->created_at->lT(Carbon::now()->subMinutes(5))) {
      $login->delete();
      return false;
    }

    $login->verified = true;
    $login->save();

    return $login;

  }

}
