<?php

namespace App\Models;
use App\Models\User;
use App\Models\Video;
use App\Models\Shot;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\OrderScope;

class Boost extends Model
{
    protected $fillable = ['user_id', 'video_id', 'status', 'views', 'delivered'];

    const options = [10, 50, 100];

    const PENDING = 'pending';
    const ACTIVE = 'active';
    const COMPLETE = 'complete';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderScope('created_at', 'desc'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
