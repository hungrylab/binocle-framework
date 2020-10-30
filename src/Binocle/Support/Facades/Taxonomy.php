<?php

namespace Binocle\Support\Facades;

/**
 * Class Taxonomy
 * @package Binocle\Support\Facades
 */
class Taxonomy extends \Binocle\Support\Facade
{
    /**
     * @return string
     */
    protected static function getContainerAccessor()
    {
        return 'taxonomy';
    }
}
