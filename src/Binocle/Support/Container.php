<?php

namespace Binocle\Support;

/**
 * Class Container
 * @package Binocle\Support
 */
class Container
{
    /**
     * @var \Pimple\Container
     */
    protected static $instance;

    /**
     * @return \Pimple\Container
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new \Pimple\Container;
        }

        return static::$instance;
    }
}
