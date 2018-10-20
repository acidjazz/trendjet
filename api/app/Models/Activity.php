<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'action', 'payload'];
    protected $casts = ['payload' => 'array'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Store activity
     *
     * @param String $action
     * @param App\Models\User $user
     * @param [Array,Object] $payload
     */
    public static function log(String $action, User $user, $payload)
    {
        return (new Activity([
            'user_id' => $user->id,
            'action' => $action,
            'payload' => $payload,
        ]))->save();
    }

}
