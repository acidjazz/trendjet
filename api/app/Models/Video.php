<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoLog;
use App\Models\Boost;
use acidjazz\tubestuff\TubeStuff;

use App\Scopes\OrderScope;

class Video extends Model
{

    public $incrementing = false;
    protected $fillable = [ 'id', 'user_id', 'title', 'views', 'duration'];
    protected $appends = ['cover'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderScope('created_at', 'desc'));
    }

    public function getCoverAttribute()
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

        $videos = (new TubeStuff)->getVideos($ids);
        foreach ($videos as $id=>$video) {
            $video['user_id'] = $user->id;
            $row = self::create($video);
            $row->logs()->create(['views' => $video['views']]);
        }
    }

}
