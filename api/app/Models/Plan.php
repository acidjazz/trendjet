<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\ActiveScope;

class Plan extends Model
{
    protected $fillable = [
        'title','price','views',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }
}
