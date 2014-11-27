<?php

namespace Binocle\Theme;

// use Hook;
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
		// Hook::add('template_include', array($this, 'wrap'));

		// Asset::deregister('jquery', 'script');
		$foundationVersion = '5.4.7';

		// add basic assets
		Asset::addScript('modernizr-foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/vendor/modernizr.js');
		Asset::addScript('jquery-foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/vendor/jquery.js', [], $foundationVersion, true);
		Asset::addScript('fastclick-foundation', get_template_directory_uri() . '/assets/bower_components/fastclick/lib/fastclick.js', [], $foundationVersion);
		Asset::addScript('foundation', get_template_directory_uri() . '/assets/bower_components/foundation/js/foundation.min.js', ['jquery-foundation'], $foundationVersion, true);
		Asset::addScript('foundation-app', get_template_directory_uri() . '/assets/js/app.js', ['foundation'], $foundationVersion, true);

		Asset::addStyle('normalize', get_template_directory_uri() . '/assets/bower_components/foundation/css/normalize.css');
		Asset::addStyle('foundation', get_template_directory_uri() . '/assets/bower_components/foundation/css/foundation.css', ['normalize'], $foundationVersion);
		Asset::addStyle('app', get_template_directory_uri() . '/assets/stylesheets/app.css', ['foundation']);
	}

	public function load()
	{
		$finder = new Template\Finder;

		// determine which template to load
		$template = false;
		if     (is_404()            && $template = $finder->get('404')					) :
		elseif (is_search()         && $template = $finder->get('search')				) :
		elseif (is_front_page()     && $template = $finder->get('front_page')			) :
		elseif (is_home()           && $template = $finder->get('home')					) :
		elseif (is_post_type_archive() && $template = $finder->get('post_type_archive') ) :
		elseif (is_tax()            && $template = $finder->get('taxonomy')				) :
		elseif (is_attachment()     && $template = $finder->get('attachment')			) :
			remove_filter('the_content', 'prepend_attachment');
		elseif (is_single()         && $template = $finder->get('single')				) :
		elseif (is_page()           && $template = $finder->get('page')					) :
		elseif (is_category()       && $template = $finder->get('category')				) :
		elseif (is_tag()            && $template = $finder->get('tag')					) :
		elseif (is_author()         && $template = $finder->get('author')				) :
		elseif (is_date()           && $template = $finder->get('date')					) :
		elseif (is_archive()        && $template = $finder->get('archive')				) :
		elseif (is_comments_popup() && $template = $finder->get('comments_popup')		) :
		elseif (is_paged()          && $template = $finder->get('paged')				) :
		else :
			$template = $finder->get('index');
		endif;

		if ($template) {
			$this->template = $template;

			return Template\Loader::load('layout.ground');
		} else {
			// throw exception
		}
	}

	/**
	 * Wraps initial template with ground layout
	 * @param string $template
	 * @return object|string
	 */
	public function wrap($template)
	{
		$groundTemplate = Template\Loader::load('layout.ground');

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
