<?php

namespace SgtCoder\LaravelFunctions\Traits;

use Illuminate\Support\{
    Arr,
    Str
};

/*
 * A trait to handle authorization based on users permissions for given controller
 */

trait Authorizable
{
    /**
     * Abilities
     *
     * @var array
     */
    private $abilities = [
        'index' => 'view',
        'edit' => 'edit',
        'show' => 'view',
        'update' => 'edit',
        'create' => 'add',
        'store' => 'add',
        'destroy' => 'delete',
        'search' => 'view',
    ];

    /**
     * Override of callAction to perform the authorization before it calls the action
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if ($ability = $this->getAbility($method)) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * Get ability
     *
     * @param $method
     * @return null|string
     */
    public function getAbility($method)
    {
        $action = Arr::get($this->getAbilities(), $method);

        $controller_class = explode('\\', get_class(request()->route()->getController()));

        $class_name = Str::snake(Str::plural(str_replace('Controller', '', end($controller_class))));

        return $action ? $action . '_' . $class_name : null;
    }

    /**
     * @return array
     */
    private function getAbilities()
    {
        return $this->abilities;
    }

    /**
     * @param array $abilities
     */
    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}
