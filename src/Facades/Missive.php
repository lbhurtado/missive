<?php

namespace LBHurtado\Missive\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LBHurtado\Missive\Skeleton\SkeletonClass
 */
class Missive extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'missive';
    }
}
