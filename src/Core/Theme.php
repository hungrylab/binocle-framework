<?php

namespace Binocle\Core;

use Binocle\Support\Container as Container;
use \Config;
use \Hook;

class Theme
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
		$this->container['hook'] = function($c) {
			return new \Binocle\Core\Hook;
		};

		$this->container['template'] = function($c) {
			return new \Binocle\Core\Template($c);
		};

		$this->container['asset'] = function($c) {
			return new \Binocle\Core\Template\Asset;
		};

		$this->container['config'] = function($c) {
			return new \Binocle\Core\Config;
		};

		$this->container['posttype'] = function($c) {
			return new \Binocle\Core\Posttype;
		};

		// set aliases
		class_alias('Binocle\Core\Facades\Asset', 'Asset');
		class_alias('Binocle\Core\Facades\Hook', 'Hook');
		class_alias('Binocle\Core\Facades\Config', 'Config');
		class_alias('Binocle\Core\Facades\Posttype', 'Posttype');
	}

	/**
	 * Boot theme
	 * @return void 
	 */
	public function boot($child = null)
	{
		// load template
		$this->container['template'];

		// clean up
		// todo

		// do some default stuff
		$this->doDefault();

		// run child
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
			foreach ($theme['support'] as $feature => $args) {
				add_theme_support($feature);
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

			// instantiate menus
			foreach ($menus as $location => $name) {
				register_nav_menu($location, $name);
			}
		});
	}
}
