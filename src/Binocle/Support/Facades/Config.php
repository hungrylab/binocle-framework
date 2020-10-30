<?php

namespace Binocle\Support\Facades;

use \Binocle\Support\Facade as Facade;

/**
 * Class Config
 * @package Binocle\Support\Facades
 */
class Config extends Facade
{
    /**
     * @return string
     */
    public static function getContainerAccessor()
    {
        return 'config';
    }
}
