<?php

namespace Binocle\Theme\Template;

use Binocle\Support;

/**
 * Class Loader
 * @package Binocle\Theme\Template
 */
class Loader
{
    /**
     * Template to load
     * @var string
     */
    private $template;

    /**
     * Loader
     * @param string $template
     * @return object
     */
    public static function load($template)
    {
        // alter template so that it works
        $template = get_template_directory() != substr($template, 0, strlen(get_template_directory())) ? $template : substr($template, strlen(get_template_directory()) + 1);
        $template = get_stylesheet_directory() != substr($template, 0, strlen(get_stylesheet_directory())) ? $template : substr($template, strlen(get_stylesheet_directory()) + 1);
        $template = '.php' != substr($template, -4) ? $template : substr($template, 0, -4);
        $template = 'theme.templates.' == substr($template, 0, 16) ? $template : 'theme.templates.' . $template;

        $mappedTemplate = static::mapTemplate($template);
        $template = locate_template($mappedTemplate);

        return $template ? new Loader($template) : null;
    }

    /**
     * Maps template
     * @param string $template
     * @return string
     */
    private static function mapTemplate($template)
    {
        $template = str_replace('.', '/', $template);
        // check template engine
        $template .= '.php';

        return $template;
    }

    /**
     * Constructor
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * Returns this template
     * @return string
     */
    public function __toString()
    {
        return $this->template;
    }
}
