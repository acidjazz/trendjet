<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boost extends Model
{
    protected $fillable = ['user_id', 'video_id', 'status', 'views', 'delivered'];

    const options = [100, 1000, 10000];

    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETE = 'complete';
}
