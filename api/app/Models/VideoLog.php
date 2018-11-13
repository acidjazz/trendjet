<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderScope;

class VideoLog extends Model
{
    protected $fillable = ['video_id', 'views'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderScope('created_at', 'desc'));
    }
}
