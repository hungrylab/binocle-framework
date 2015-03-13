<?php

namespace Binocle\Support;

class Path
{
	private $templatePath;
	private $templateUrl;
	private $stylesheetPath;
	private $stylesheetUrl;

	public function __construct()
	{
		$this->templatePath = get_template_directory();
		$this->templateUrl = get_template_directory_uri();

		if ($this->templatePath != get_stylesheet_directory()) {
			$this->stylesheetPath = get_stylesheet_directory();
			$this->stylesheetUrl = get_stylesheet_directory_uri();
		}
	}

	public function find($path, $overload = true)
	{
		$path = $this->mapPath($path);
		$path = '.php' != substr($path, -4) ? $path . '.php' : $path;
		$path = '/' == substr($path, 0, 1) ? substr($path, 1) : $path;

		if ($overload) {
			return locate_template($path);
		} else {
			$files = array();
			if (is_readable($this->templatePath . '/' . $path)) {
				$files[] = $this->templatePath . '/' . $path;
			}

			if ($this->stylesheetPath && is_readable($this->stylesheetPath . '/' . $path)) {
				$files[] = $this->stylesheetPath . '/' . $path;
			}

			return $files;
		}
	}

	public function mapPath($path)
	{
		return str_replace('.', '/', $path);
	}
}
