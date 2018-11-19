<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderScope;

class Shot extends Model
{
    protected $fillable = ['video_id', 'boost_id', 'file', 'duration'];
    protected $appends = ['url'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderScope('created_at', 'desc'));
    }

    protected function getUrlAttribute()
    {
        return 'https://s3.amazonaws.com/trendjet-shots/'.$this->file;
    }

}
