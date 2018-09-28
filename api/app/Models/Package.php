<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Purchase;
use App\Scopes\ActiveScope;

class Package extends Model
{
    protected $fillable = [
        'title','price','views',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
