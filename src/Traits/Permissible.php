<?php

namespace MichaelCooke\Guardian\Traits;

use Illuminate\Support\Collection;

trait Permissible
{
    /**
     * Get the model's permissions.
     *
     * @param  string  $permissions
     * @return Illuminate\Support\Collection
     */
    public function getPermissionsAttribute($permissions): Collection
    {
        return collect(json_decode($permissions, true));
    }

    /**
     * Determine if the model has access with a permission key.
     *
     * @param  string  $permissionKey
     * @return boolean
     */
    public function hasAccess(string $permissionKey): bool
    {
        foreach ($this->getPermissions() as $key => $value) {
            if (fnmatch($key, $permissionKey)) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Get all inherited permissions for the model.
     *
     * @return Illuminate\Support\Collection
     */
    public function getPermissions(): Collection
    {
        $permissions = $this->permissions;

        if ($this->inheritsAccessFrom != null) {
            foreach ($this->inheritsAccessFrom as $model) {
                $inheritedModels = $this->{$model}()->get();
                if ($inheritedModels->isNotEmpty()) {
                    foreach ($inheritedModels as $model) {
                        $inheritedModelPermissions = $model->permissions;
                        $permissions = $this->mergePermissions($permissions, $inheritedModelPermissions);
                    }
                }
            }
        }

        return $permissions;
    }

    /**
     * Add or override an existing permission for the model.
     *
     * @return boolean
     */
    public function addPermission(string $permissionKey, bool $allow = true): bool
    {
        $this->permissions = $this->permissions->put($permissionKey, $allow)->toJson();
        return $this->save();
    }

    /**
     * Alias for addPermission().
     *
     * @return boolean
     */
    public function updatePermission(string $permissionKey, bool $allow = true): bool
    {
        $this->addPermission($permissionKey, $allow);
    }

    /**
     * Remove a permission from the model.
     *
     * @return boolean
     */
    public function removePermission(string $permissionKey): bool
    {
        $this->permissions = $this->permissions->forget($permissionKey)->toJson();
        return $this->save();
    }

    /**
     * Merge two permissions collections.
     *
     * @return Illuminate\Support\Collection
     */
    protected function mergePermissions(Collection $collectionOne, Collection $collectionTwo): Collection
    {
        foreach ($collectionTwo as $key => $value) {
            if ($collectionOne->get($key) == null ||
                $collectionOne->get($key) && !$collectionTwo->get($key)) {
                $collectionOne->put($key, $value);
            }
        }
        return $collectionOne;
    }
}
