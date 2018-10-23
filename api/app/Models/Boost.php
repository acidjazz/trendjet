<?php

namespace App\Models;
use App\Models\User;
use App\Models\Video;
use App\Models\Shot;

use Illuminate\Database\Eloquent\Model;

class Boost extends Model
{
    protected $fillable = ['user_id', 'video_id', 'status', 'views', 'delivered'];

    const options = [100, 1000, 10000];

    const PENDING = 'pending';
    const ACTIVE = 'active';
    const COMPLETE = 'complete';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
