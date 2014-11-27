<?php

namespace Binocle\Component;

class Posttype
{
	/**
	 * Keeps all custom post types
	 * @var array
	 */
	private $posttypes;
	/**
	 * Default queries
	 * @var array
	 */
	private $queries;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		Hook::add('after_setup_theme', array($this, 'registerPosttypes'));
		Hook::add('pre_get_posts', [$this, 'setDefaultQuery']);
	}

	/**
	 * Add new posttype
	 * @param string $typeName
	 */
	public function add($typeName, $args)
	{
		// set default
		$args = array_merge_recursive(array(
			'public' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
			),
			'has_archive' => true,
		), $args);

		if (isset($args['query'])) {
			$this->queries[$typeName] = $args['query'];

			unset($args['query']);
		}

		$this->posttypes[$typeName] = $args;

		return true;
	}

	/**
	 * Registers post types, called by hook
	 * @return void
	 */
	public function registerPosttypes()
	{
		foreach ($this->posttypes as $typeName => $args) {
			register_post_type($typeName, $args);
		}

		return;
	}

	/**
	 * Set default query parameters
	 * @param  object $query
	 * @return null
	 */
	public function setDefaultQuery($query)
	{
		if ($this->queries && $query->is_main_query() && is_post_type_archive()) {
			if (in_array($query->query['post_type'], array_keys($this->queries))) {
				foreach ($this->queries[$query->query['post_type']] as $argument => $value) {
					$query->set($argument, $value);
				}
			}
		}
	}
}
