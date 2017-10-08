<?php

namespace MichaelCooke\Guardian\Traits;

trait Permissible
{
    /**
     * Returns all access definitions for a permissible model.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAccessDefinitions(String $accessDefinition)
    {
        /*
         * In order to store all directly assigned and inherited access
         * definitions for the permissible model, we create a new
         * collection that we'll return later. We'll also get
         * any directly assigned access definitions for
         * the permissible model so we can add them
         * to the new collection.
         */
        $accessDefinitions = collect([]);
        $modelAccessDefinitions = $this->{$accessDefinition}()->get();

        /*
         * If there are directly assigned access definitions for the
         * permissible model, we need to iterate over each of
         * them and put them in the new collection we're
         * returning later on.
         */
        if ($modelAccessDefinitions->isNotEmpty()) {
            foreach ($modelAccessDefinitions as $modelAccessDefinition) {
                $accessDefinitions->push($modelAccessDefinition);
            }
        }

        /*
         * If the permissible model inherits access definitions from other
         * permissible models, we need to iterate over each of them to
         * get their assigned access definitions which will be
         * inherited by the permissible model we're
         * getting all access definitions for.
         */
        if ($this->inheritsAccessFrom != null) {
            foreach ($this->inheritsAccessFrom as $permissibleModel) {
                $inheritedModels = $this->{$permissibleModel}()->get();

                /*
                 * If the permissible model belongs to multiple instances of
                 * a model the original permissible model inherits access
                 * definitions from, we need to iterate over each one
                 * them to get their access definitions.
                 */
                if ($inheritedModels->isNotEmpty()) {
                    foreach ($inheritedModels as $inheritedModel) {
                        $inheritedModelAccessDefinitions = $inheritedModel->{$accessDefinition}()->get();

                        /*
                         * If the instance of an inherited model has access
                         * definitions, we need to iterate over each of
                         * them and add them to the collection we're
                         * returning.
                         */
                        if ($inheritedModelAccessDefinitions->isNotEmpty()) {
                            foreach ($inheritedModelAccessDefinitions as $inheritedModelAccessDefinition) {
                                $accessDefinitions->push($inheritedModelAccessDefinition);
                            }
                        }
                    }
                }
            }
        }

        return $accessDefinitions;
    }
    
    /**
     * Returns all permissions explicitly assigned and inherited for a
     * permissible model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions()
    {
        return $this->getAccessDefinitions('permissions');
    }

    /**
     * Returns all restrictions explicitly assigned and inherited for a
     * permissible model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRestrictions()
    {
        return $this->getAccessDefinitions('restrictions');
    }

    /**
     * Determines whether or not a permissible model has access with a given
     * permission key, evaluating all directly assigned and inherited
     * permissions and restrictions.
     *
     * @param  String $key
     * @return boolean
     */
    public function hasAccess(String $key)
    {
        return !$this->hasRestriction($key) && $this->hasPermission($key);
    }

    /**
     * Determines whether or not a permissible model has a particular access
     * definition.
     *
     * @param  String $key
     * @param  String $permissibleModel
     * @return boolean
     */
    protected function hasAccessDefinition(String $key, String $permissibleModel)
    {
        $accessDefinitions = $this->getAccessDefinitions($permissibleModel);

        foreach ($accessDefinitions as $accessDefinition) {
            if (fnmatch($accessDefinition->key, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines whether or not a permissible model has a particular
     * permission.
     *
     * @param  String $key
     * @return boolean
     */
    public function hasPermission(String $key)
    {
        return $this->hasAccessDefinition($key, 'permissions');
    }

    /**
     * Determines whether or not a permissible model has a particular
     * restriction.
     *
     * @param  String $key
     * @return boolean
     */
    public function hasRestriction(String $key)
    {
        return $this->hasAccessDefinition($key, 'restrictions');
    }
}
