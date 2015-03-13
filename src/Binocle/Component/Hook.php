<?php

namespace Binocle\Component;

use Binocle\Support;

class Hook
{
	/**
	 * Add hook
	 * @param string $hook
	 * @param mixed $action
	 * @param string $type
	 * @return bool
	 */
	public function add($hook, $action, $type = 'action', $priority = 10, $numArgs = 1)
	{
		$function = 'add_' . $type;
		return $function($hook, $action, $priority, $numArgs);
	}

	/**
	 * Remove hook
	 * @param  string $hook
	 * @param  mixed $action
	 * @param  string $type
	 * @return bool
	 */
	public function remove($hook, $action, $type = 'action', $priority = 10)
	{
		$func = 'remove_' . $type;
		return $func($hook, $action, $priority);
	}
}
