<?php

namespace App\Models;

use App\Models\Package;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [ 'package_id', 'user_id', 'views' ];

    public function plan()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
