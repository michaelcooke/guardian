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
        'name', 'slug',
    ];

    /**
     * A role has many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->belongsToMany('MichaelCooke\Guardian\User', 'user_roles');
    }
}
