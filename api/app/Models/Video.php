<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoHistory;

class Video extends Model
{

  protected $fillable = [ 'name' ];

  public function history()
  {
    return $this->hasMany(VideoHistory::class);
  }
}
