<?php

namespace Binocle\Core;

class Posttype
{
	/**
	 * Keeps all custom post types
	 * @var array
	 */
	private $posttypes;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		Hook::add('after_setup_theme', array($this, 'registerPosttypes'));
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
}
