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

## Using Guardian

### Access Definitions

"Access definition" is just a fancy term to describe either a `Permission` or a `Restriction`. Access definitions may be used to control access to particular features or functions in your application.

### Access

To check whether or not a user or role has access to a particular function or feature, simply call `hasPermission()` with a permission value. It will return a boolean value based on whether or not access is granted.

Access is determined by a permissible model's inherited and directly assigned permissions and restrictions. If a permissible model's inherited access contains permission to the key being evaluated, and no restriction exists on either the model or it's inherited access, access will be granted.

In the event of a permissions conflict with a restriction during an access evaluation -- due to access inheritence or otherwise -- a restriction will *always* take precedence and prevent access to a matching permission, regardless of whether or not permission was assigned directly to the evaulated model.

```
if ($user->hasAccess('blog.post')) {
    // User has access
}
```

### Access Inheritence

By default, a User will inherit all access definitions from any roles they are associated with; All permissions and restrictions from a user's roles will trickle down and to apply to the user. Access definitions obtained through inheritence are not weighed less than directly assigned access definitions during access evaluation.

#### Permissions

Permissions allow you to grant a user access to a particular feature or function in your app. A permission in its basic form may be something like `blog.post`, wherein permission to create a new blog post is defined.

All directly assigned and inherited permissions for a permissible model may be accessed through the `getPermissions()` function.

In addition, all permissible models may be checked to determine whether or not it has a permission assigned either directly or via inheritence with `hasPermission()`

```
if ($user->hasPermission('blog.post')) {
    // User has permission
}
```

#### Restrictions

Restrictions allow you to block a particular user or role from having access to a feature or function in your app, even if permission was otherwise assigned. This is particularly useful for a "banned" role, where a user should be barred from accessing most of your app, despite any other permissions potentially assigned.

All directly assigned and inherited restrictions for a permissible model may be accessed through the `getRestrictions()` function.

In addition, all permissible models may be checked to determine whether or not it has a restriction assigned either directly or via inheritence with `hasRestriction()`

```
if ($user->hasRestriction('blog.post')) {
    // User has restriction
}
```

#### Wildcards

Guardian uses UNIX-style glob pattern matching in access definition evaluation. This grants access to several wildcard operators, the most relevant of which is `*` to match all characters including none at all.

[More information on glob pattern matching is available on Wikipedia.](https://en.wikipedia.org/wiki/Glob_(programming)#Syntax)

## Extending Guardian

### Permissible Models and the Permissible Trait

A "permissible model" is simply a model that uses the `Permissible` trait included with Guardian. It allows models to be associated with access definitions, and inherit access definitions from other permissible models.

For example, a user will inherit access definitions from their associated roles out of the box. This is possible because both the user and role models use the `Permissible` trait, and the user model is configured to inherit access definitions from any role that may be associated with the user.

Access for a particular permissible model is inherited through whatever other permissible models are defined in a model's `$inheritsAccessFrom` property.