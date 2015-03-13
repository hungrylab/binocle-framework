<?php

namespace Binocle\Theme;

use Binocle\Support\Container as Container;
use Config;
use Hook;

class Loader
{
	/**
	 * Instance of IoC DIC
	 * @var object
	 */
	private $container;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->container = Container::getInstance();

		// set dependencies
		if (!isset($this->container['hook'])) {
			$this->container['hook'] = function($c) {
				return new \Binocle\Component\Hook;
			};
		}

		$this->container['template'] = function($c) {
			return new \Binocle\Theme\Template($c);
		};

		$this->container['asset'] = function($c) {
			return new \Binocle\Theme\Template\Asset;
		};

		$this->container['config'] = function($c) {
			return new \Binocle\Support\Config;
		};

		if (!isset($this->container['posttype'])) {
			$this->container['posttype'] = function($c) {
				return new \Binocle\Component\Posttype;
			};
		}

		$this->container['path'] = function($c) {
			return new \Binocle\Support\Path;
		};

		// set aliases
		class_alias('Binocle\Support\Facades\Template', 'Template');
		class_alias('Binocle\Support\Facades\Asset', 'Asset');
		if (!class_exists('Hook')) {
			class_alias('Binocle\Support\Facades\Hook', 'Hook');
		}
		class_alias('Binocle\Support\Facades\Config', 'Config');
		if (!class_exists('Posttype')) {
			class_alias('Binocle\Support\Facades\Posttype', 'Posttype');
		}
		class_alias('Binocle\Support\Facades\Path', 'Path');
	}

	/**
	 * Boot theme
	 * @return void
	 */
	public function boot($child = null)
	{
		// load template
		// $this->container['template'];

		// clean up
		// todo

		// do some default stuff
		$this->doDefault();

		// run child
		if ($functions = \Path::find('theme.functions')) {
			include($functions);
		}

		if ($child) {
			$child();
		}
	}

	private function doDefault()
	{
		$theme = Config::get('theme');
		$menus = Config::get('menu');

		Hook::add('after_setup_theme', function() use ($theme, $menus) {
			// add theme support
			if (isset($theme['support'])) {
				foreach ($theme['support'] as $feature => $args) {
					add_theme_support($feature);
				}
			}

			// add image sizes
			if (isset($theme['image'])) {
				foreach ($theme['image'] as $name => $args) {
					$args = array_merge(array(
						'width' => 0,
						'height' => 0,
						'crop' => false,
					), $args);

					add_image_size($name, $args['width'], $args['height'], $args['crop']);
				}
			}

			// add sidebars
			if (isset($theme['sidebar'])) {
				foreach ($theme['sidebar'] as $name => $args) {
					if (isset($args['number'])) {
						$number = $args['number'];
						unset($args['number']);

						if (!preg_match('/\%\d/', $name)) {
							$name = $name . ' %d';
						}

						register_sidebars($number, ['name' => $name] + $args);
					} else {
						register_sidebar(['name' => $name] + $args);
					}
				}
			}

			// instantiate menus
			foreach ($menus as $location => $name) {
				register_nav_menu($location, $name);
			}
		});
	}
}
