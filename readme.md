# Guardian

Guardian is a simple permissions and roles package for Laravel that provides permissions, restrictions, roles, and easily configured access inheritence.

## Installing Guardian

### Require Guardian via Composer

Installation is a cinch; Simply require Guardian through Composer to pull the package into your project.

```
composer require michaelcooke/guardian
```

### Run Migrations

Then, run Guardian's migrations via Artisan.

```
php artisan migrate
```

### Configure Aliases

After that, include Guardian's facades in the `aliases` array of your project's `config/app.php` file.

```
'Permission' => MichaelCooke\Guardian\Facades\Permission::class,
'Restriction' => MichaelCooke\Guardian\Facades\Restriction::class,
'Role' => MichaelCooke\Guardian\Facades\Role::class,
```

### Configure App User Model


Finally, configure Laravel to use Guardian's User model by editting the users provider configuration array in the  `config/auth.php`.

```
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => MichaelCooke\Guardian\User::class,
    ],
```

### Extend Guardian User Model

Instead of configuring Laravel to use Guardian's User model directly, you may alternatively extend Guardian's User model in `app/User.php` to make use of Guardian while being able to add custom relationships and attributes to your User model for your app's specific needs.

```
<?php

namespace App;

use MichaelCooke\Guardian\User as GuardianUser;

class User extends GuardianUser
{
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
}
```