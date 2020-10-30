<?php

namespace Binocle\Support\Facades;

/**
 * Class Path
 * @package Binocle\Support\Facades
 */
class Path extends \Binocle\Support\Facade
{
    /**
     * @return string
     */
    protected static function getContainerAccessor()
    {
        return 'path';
    }
}
