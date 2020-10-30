<?php

namespace Binocle\Support\Facades;

/**
 * Class Hook
 * @package Binocle\Support\Facades
 */
class Hook extends \Binocle\Support\Facade
{
    /**
     * @return string
     */
    protected static function getContainerAccessor()
    {
        return 'hook';
    }
}
