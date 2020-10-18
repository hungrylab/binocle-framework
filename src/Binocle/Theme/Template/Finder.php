<?php

namespace Binocle\Theme\Template;

class Finder
{
	private $templates = [
		'archive' => [
			'archive-{post_type}.php',
			'archive.php',
		],
		'author' => [
			'author-{user_nicename}.php',
			'author-{ID}.php',
			'author.php',
		],
		'category' => [
			'category-{slug}.php',
			'category-{term_id}.php',
			'category.php',
		],
		'tag' => [
			'tag-{slug}.php',
			'tag-{term_id}.php',
			'tag.php',
		],
		'taxonomy' => [
			'taxonomy-{taxonomy}-{slug}.php',
			'taxonomy-{taxonomy}.php',
			'taxonomy.php',
		],
		'home' => [
			'home.php',
			'index.php',
		],
		'front_page' => [
			'front_page.php',
		],
		'page' => [
			'{template}',
			'page-{pagename}.php',
			'page-{id}.php',
			'page.php',
		],
		'single' => [
			'single-{post_type}.php',
			'single.php',
		],
		'comments_popup' => [
			'comments_popup.php',
		]
	];

	private $varRegex = '/\{([a-z_]+)\}/';

	public function get($type)
	{
		// prepare post type archive
		if ('post_type_archive' == $type) {
			$postType = get_query_var('post_type');
			if (is_array($postType)) {
				$postType = reset($postType);
			}

			$obj = get_post_type_object($postType);
			if (!$obj->has_archive) {
				return;
			}

			$type = 'archive';
		}

		$templates = $this->getTemplates($type);
		if (!$templates) {
			$templates = [$type . '.php'];
		} else if ($this->hasReplaceableVars($templates)) {
			$templates = $this->replaceVarTemplates($type, $templates);
		}

		// check if exists
		foreach ($templates as $template) {
			if ($loader = Loader::load($template)) {
				return $loader;
			}
		}

		return null;
		// todo: attachment
	}

	private function getTemplates($type)
	{
		return isset($this->templates[$type]) ? $this->templates[$type] : null;
	}

	private function hasReplaceableVars($templates) {
		foreach ($templates as $template) {
			if (preg_match($this->varRegex, $template)) {
				return true;
			}
		}

		return false;
	}

	private function replaceVarTemplates($type, $templates)
	{
		$method = 'replace' . ucfirst(strtolower($type)) . 'VarTemplates';
		if (method_exists($this, $method)) {
			return $this->{$method}($templates);
		}

		$object = get_queried_object();

		if (empty($object->slug)) {
			return $templates;
		}

		foreach ($templates as &$template) {
			preg_match_all($this->varRegex, $template, $vars, PREG_SET_ORDER);
			if (count($vars)) {
				foreach ($vars as $var) {
					$template = str_replace($var[0], $object->{$var[1]}, $template);
				}
			}
		}

		return $templates;
	}

	private function replaceArchiveVarTemplates($templates)
	{
		$postTypes = array_filter((array)get_query_var('post_type'));
		$postType = '';
		if (count($postTypes) == 1) {
			$postType = reset($postTypes);
		}

		foreach ($templates as &$template) {
			$template = str_replace('{post_type}', $postType, $template);
		}

		return $templates;
	}

	private function replaceSingleVarTemplates($templates)
	{
		$object = get_queried_object();

		if (!empty($object->post_type)) {
			return $templates;
		}

		foreach ($templates as &$template) {
			$template = str_replace('{post_type}', $object->post_type, $template);
		}

		return $templates;
	}
}
