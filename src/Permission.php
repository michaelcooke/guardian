<?php

namespace MichaelCooke\Guardian;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'description',
    ];

    /**
     * A permission has many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\Role', 'role_permissions')->withPivot('restrict');
    }

    /**
     * A permission has many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\User', 'user_permissions')->withPivot('restrict');
    }
}
