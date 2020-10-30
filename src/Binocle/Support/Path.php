<?php

namespace Binocle\Support;

/**
 * Class Path
 * @package Binocle\Support
 */
class Path
{
    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var string
     */
    private $templateUrl;

    /**
     * @var string
     */
    private $stylesheetPath;

    /**
     * @var string
     */
    private $stylesheetUrl;

    /**
     * Path constructor.
     */
    public function __construct()
    {
        $this->templatePath = get_template_directory();
        $this->templateUrl = get_template_directory_uri();

        if ($this->templatePath !== get_stylesheet_directory()) {
            $this->stylesheetPath = get_stylesheet_directory();
            $this->stylesheetUrl = get_stylesheet_directory_uri();
        }
    }

    /**
     * @param $path
     * @param bool $overload
     * @return array
     */
    public function find($path, $overload = true)
    {
        $path = $this->mapPath($path);
        $path = '.php' != substr($path, -4) ? $path . '.php' : $path;
        $path = '/' == substr($path, 0, 1) ? substr($path, 1) : $path;

        if ($overload) {
            return locate_template($path);
        } else {
            $files = [];
            if (is_readable($this->templatePath . '/' . $path)) {
                $files[] = $this->templatePath . '/' . $path;
            }

            if ($this->stylesheetPath && is_readable($this->stylesheetPath . '/' . $path)) {
                $files[] = $this->stylesheetPath . '/' . $path;
            }

            return $files;
        }
    }

    /**
     * @param $path
     * @return string|string[]
     */
    public function mapPath($path)
    {
        return str_replace('.', '/', $path);
    }
}
