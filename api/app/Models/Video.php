<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoLog;
use App\Models\Boost;
use acidjazz\tubestuff\TubeStuff;

use App\Scopes\OrderScope;

class Video extends Model
{

  protected $fillable = [ 'id', 'user_id', 'title', 'views'];
  public $incrementing = false;
  protected $appends = [ 'cover'];

  protected static function boot()
  {
    parent::boot();
    static::addGlobalScope(new OrderScope('created_at', 'asc'));
  }

  public function getCoverAttribute ()
  {
    return TubeStuff::cover($this->id);
  }

  public function logs()
  {
    return $this->hasMany(VideoLog::class);
  }

  public function boosts()
  {
    return $this->hasMany(Boost::class);
  }

  /**
   * Add videos from an array
   *
   * @param App\Models\User $user
   * @param Array $ids
   * @return mixed
   */
  public static function add($user, $ids) {

    $ts = new TubeStuff(config('services.google.api_key'));
    $videos = $ts->getVideos($ids);

    foreach ($videos as $id=>$video) {
      $video['user_id'] = $user->id;
      $row = self::create($video);
      $row->logs()->create(['views' => $video['views']]);
    }
  }

}
