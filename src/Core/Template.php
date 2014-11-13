<?php

namespace Binocle\Core;

use Hook;
use Asset;

class Template
{
	/**
	 * Holds initial template
	 * @var object|string
	 */
	protected $template;

	/**
	 * Constructor
	 * @param object $container
	 */
	public function __construct($container)
	{
		$this->container = $container;

		// load wrapper if needed
		Hook::add('template_include', array($this, 'wrap'));

		// Asset::deregister('jquery', 'script');

		// add basic assets
		Asset::addScript('modernizr-foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/vendor/modernizr.js');
		Asset::addScript('jquery-foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/vendor/jquery.js', array(), '5.4.3', true);
		Asset::addScript('fastclick-foundation', get_template_directory_uri() . '/assets/bower_components/fastclick/lib/fastclick.js', array(), '5.4.3');
		Asset::addScript('foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/foundation.min.js', array('jquery-foundation'), '5.4.3', true);
		Asset::addScript('foundation-app', get_template_directory_uri() . '/assets/js/app.js', array('foundation'), '5.4.3', true);

		Asset::addStyle('normalize', get_template_directory_uri() . '/assets/bower_components/foundation/css/normalize.css');
		Asset::addStyle('foundation', get_template_directory_uri() . '/assets/bower_components/foundation/css/foundation.css');
		Asset::addStyle('app', get_template_directory_uri() . '/assets/css/app.css');
	}

	/**
	 * Wraps initial template with ground layout
	 * @param string $template
	 * @return object|string
	 */
	public function wrap($template)
	{
		$this->template = Template\Loader::load($template);
		$groundTemplate = Template\Loader::load('templates.layout.ground');

		return $groundTemplate;
	}

	/**
	 * Loads initial template
	 * @return void 
	 */
	public function loadTemplate()
	{
		include($this->template);
	}
}
