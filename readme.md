# Guardian

Guardian is a simple permissions and roles package for Laravel that provides permissions, roles and easily configurable access inheritence.

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

### Permissions

Permissions may be used to control access to particular features or functions in your application. They're defined by a key, as well as a corresponding boolean value that controls whether or not access to that permission key should be granted or blocked in all circumstances.

You're free to make these keys whatever you want, but adhering to a granular format is recommended for usage in your applications. An example for a forum section in your application may look like this:

```
forums.*
    forums.admin.*
        forums.admin.create
        forums.admin.edit
        forums.admin.delete
    forums.moderate.*
        forums.moderate.{forumId}.*
            forums.moderate.{forumId}.lock
            forums.moderate.{forumId}.move
            forums.moderate.{forumId}.pin
    forums.general.*
        forums.general.{forumId}.*
            forums.general.{forumId}.create
            forums.general.{forumId}.edit
            forums.general.{forumId}.delete
            forums.general.{forumId}.reply
```

### Access

To check whether or not a user or role has access to a particular permission key, simply call `hasPermission()` with a permission value. It will return a boolean value based on whether or not access is granted.

```
if ($user->hasAccess('blog.post')) {
    // User has access
}
```

Access is determined by a model's inherited and directly assigned permissions. If a model's inherited access contains permission to the key being evaluated, and no restriction exists on either the model or it's inherited access, access will be granted.

In the event of a permissions conflict with a restricted permission during an access evaluation -- due to access inheritence or otherwise -- the restriction will *always* take precedence and prevent access to a matching permission, regardless of whether or not permission was assigned directly to the evaulated model. This is particularly useful for a "banned" role, where a user should be barred from accessing a particular portion of your app, despite any other permissions potentially assigned.

### Access Inheritence

By default, a User will inherit all permissions from any roles they are associated with; All permissions from a user's roles will trickle down and to apply to the user. Permissions obtained through inheritence are not weighed differently than directly assigned permissions.

### Wildcards

Guardian uses UNIX-style glob pattern matching in permission evaluation. This grants access to several wildcard operators, the most relevant of which is `*` to match all characters including none at all.

[More information on glob pattern matching is available on Wikipedia.](https://en.wikipedia.org/wiki/Glob_(programming)#Syntax)

## Extending Guardian

### Permissible Models and the Permissible Trait

A "permissible model" is simply a model that uses the `Permissible` trait included with Guardian. It allows models to be associated with permissions, and inherit permissions from other permissible models.

For example, a user will inherit permissions from their associated roles out of the box. This is possible because both the user and role models use the `Permissible` trait, and the user model is configured to inherit permissions from any role that may be associated with the user.

Access for a particular permissible model is inherited through whatever other permissible models are defined in a model's `$inheritsAccessFrom` property.
