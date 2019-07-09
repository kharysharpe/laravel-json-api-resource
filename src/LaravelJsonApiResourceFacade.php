<?php

namespace Kharysharpe\LaravelJsonApiResource;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kharysharpe\LaravelJsonApiResource\Skeleton\SkeletonClass
 */
class LaravelJsonApiResourceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-json-api-resource';
    }
}
