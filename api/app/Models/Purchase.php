<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [ 'plan_id', 'user_id' ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
