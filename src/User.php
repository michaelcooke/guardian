<?php

namespace MichaelCooke\Guardian;

use Illuminate\Notifications\Notifiable;
use MichaelCooke\Guardian\Traits\Permissible;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Permissible;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Permissible models that access definitions are inherited from.
     *
     * @var array
     */
    protected $inheritsAccessFrom = [
        'roles',
    ];

    /**
     * A user has many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\Permission', 'user_permissions');
    }

    /**
     * A user has many restrictions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restrictions()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\Restriction', 'user_restrictions');
    }

    /**
     * A user has many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\Role', 'user_roles');
    }
}
