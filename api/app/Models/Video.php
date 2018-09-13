<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoLog;
use App\Services\YouTubeService;

use App\Scopes\OrderScope;

class Video extends Model
{

  protected $fillable = [ 'id', 'user_id', 'title'];
  public $incrementing = false;
  protected $appends = [ 'cover', 'current'];

  protected static function boot()
  {
    parent::boot();
    static::addGlobalScope(new OrderScope('created_at', 'asc'));
  }

  public function getCoverAttribute ()
  {
    return YouTubeService::cover($this->id);
  }

  public function getCurrentAttribute()
  {
    return $this->logs->first()['views'];
  }

  public function logs()
  {
    return $this->hasMany(VideoLog::class)->orderBy('created_at', 'DESC');
  }

  /**
   * Add videos from an array
   *
   * @param App\Models\User $user
   * @param Array $ids
   * @return mixed
   */
  public static function add($user, $ids) {

    $ys = new YouTubeService();
    $videos = $ys->getVideos($ids);

    foreach ($videos as $id=>$video) {
      $row = self::create([
        'id' => $id, 
        'user_id' => $user->id,
        'title' => $video['title'], 
      ]);

      $row->logs()->create(['views' => $video['views']]);
    }
  }

}
