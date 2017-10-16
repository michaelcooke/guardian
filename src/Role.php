<?php

namespace MichaelCooke\Guardian;

use MichaelCooke\Guardian\Role;
use Illuminate\Database\Eloquent\Model;
use MichaelCooke\Guardian\Traits\Permissible;

class Role extends Model
{
    use Permissible;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    /**
     * A role has many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\Permission', 'role_permissions')->withPivot('restrict');
    }

    /**
     * A role has many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\User', 'user_permissions');
    }
}
