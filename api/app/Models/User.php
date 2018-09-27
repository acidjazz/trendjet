<?php

namespace App\Models;

use App\Models\Provider;
use App\Models\Purchase;

use acidjazz\Humble\Traits\Humble;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Humble;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'avatar',
    ];

    public function providers()
    {
      return $this->hasMany(Provider::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getStatsAttribute()
    {
        $videos = Video::where('user_id', $this->id)->count();
        $views = [
            'purchased' => (integer)
                Purchase::where('user_id', $this->id)
                ->join('plans', ['plan_id' => 'plans.id'])->sum('plans.views')
        ];

        return [
            'videos' => $videos,
            'views' => $views,
        ];
    }
}
