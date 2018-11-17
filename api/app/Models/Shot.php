<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shot extends Model
{
    protected $fillable = ['video_id', 'boost_id', 'file', 'duration'];
    protected $appends = ['url'];

    protected function getUrlAttribute()
    {
        return 'https://s3.amazonaws.com/trendjet-shots/'.$this->file;
    }

}
