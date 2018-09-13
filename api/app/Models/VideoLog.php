<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLog extends Model
{
  protected $fillable = ['video_id', 'views'];
  public $timestamps = false;
}
