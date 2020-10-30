<?php

namespace Binocle\Support\Facades;

use \Binocle\Support\Facade as Facade;

/**
 * Class Asset
 * @package Binocle\Support\Facades
 */
class Asset extends Facade
{
    /**
     * @return string
     */
    public static function getContainerAccessor()
    {
        return 'asset';
    }
}
