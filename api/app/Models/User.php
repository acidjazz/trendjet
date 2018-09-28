<?php

namespace App\Models;

use App\Models\Provider;
use App\Models\Package;
use App\Models\Purchase;

use App\Scopes\ActiveScope;

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
                ->join('packages', ['package_id' => 'packages.id'])->sum('packages.views'),
        ];
        $views['available'] = $views['purchased'];

        return [
            'videos' => $videos,
            'views' => $views,
        ];
    }
}
