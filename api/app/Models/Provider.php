<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
  public static $allowed = ['google','facebook'];
  protected $fillable = ['user_id','name','avatar','payload'];
  protected $casts = ['payload' => 'array'];
}
