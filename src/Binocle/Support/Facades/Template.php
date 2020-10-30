<?php

namespace Binocle\Support\Facades;

/**
 * Class Template
 * @package Binocle\Support\Facades
 */
class Template extends \Binocle\Support\Facade
{
    /**
     * @return string
     */
    protected static function getContainerAccessor()
    {
        return 'template';
    }
}
