<?php

namespace Binocle\Theme\Template;

use Hook;

class Asset
{
	/**
	 * Contains all assets
	 * @var array
	 */
	private $assets;

	private $deregister;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// add enqueue hook
		Hook::add('wp_enqueue_scripts', array($this, 'enqueue'), 'action', 1);
	}

	/**
	 * Adds script
	 * @param string  $handle
	 * @param string  $src
	 * @param array   $deps
	 * @param boolean $ver
	 * @param boolean $inFooter
	 * @return boolean
	 */
	public function addScript($handle, $src = null, $deps = array(), $ver = false, $inFooter = false)
	{
		$args = func_get_args();
		array_shift($args);
		return $this->add($handle, 'script', $args);
	}

	/**
	 * Adds style
	 * @param string  $handle
	 * @param string  $src
	 * @param array   $deps
	 * @param boolean $ver
	 * @param boolean $media
	 * @return boolean
	 */
	public function addStyle($handle, $src = null, $deps = array(), $ver = false, $media = false)
	{
		$args = func_get_args();
		array_shift($args);
		return $this->add($handle, 'style', $args);
	}

	/**
	 * Generic add function
	 * @param string $handle
	 * @param string $type
	 * @param array  $args
	 * @return boolean
	 */
	public function add($handle, $type, $args = array())
	{
		list($src, $deps, $ver, $last) = $args + [null, null, null, null];

		$data = [];
		if ($src)
			$data['src'] = $src;

		if ($deps)
			$data['deps'] = $deps;

		if ($ver)
			$data['ver'] = $ver;

		if (null !== $last)
			$data[('script' == $type ? 'in_footer' : 'media')] = $last;

		$this->assets[$type][$handle] = $data;

		return true;
	}

	/**
	 * Removes asset, when no type is given it tries to find it
	 * @param  string $handle
	 * @param  string $type
	 * @return bool
	 */
	public function remove($handle, $type = null)
	{
		if (!$type) {
			// try and find the right type
			$script = false !== array_search($handle, array_keys($this->assets['script']));
			$style = false !== array_search($handle, array_keys($this->assets['style']));

			if ($script && $style) {
				// found handle in both types
				throw new \RuntimeException('Asset with handle "' . $handle . '" found in both script as style type.');
			} else {
				$type = $script ? 'script' : 'style';
			}
		}

		if (isset($this->assets[$type][$handle])) {
			unset($this->assets[$type][$handle]);
		}

		return true;
	}

	public function deregister($handle, $type)
	{
		$this->deregister[$type][] = $handle;

		return true;
	}

	/**
	 * Enqueues all assets
	 * @return void
	 */
	public function enqueue()
	{
		if ($this->deregister) {
			foreach ($this->deregister as $type => $handles) {
				$function = 'wp_deregister_' . $type;
				foreach ($handles as $handle) {
					$function($handle);
				}
			}
		}

		if ($this->assets) {
			foreach ($this->assets as $type => $assets) {
				$registerFunc = 'wp_register_' . $type;
				$enqueueFunc = 'wp_enqueue_' . $type;
				foreach ($assets as $handle => $data) {
					if ($data) {
						$data = $data + [
							'src' => null,
							'deps' => array(),
							'ver' => false,
							'in_footer' => true,
							'media' => 'all',
						];

						$lastArg = 'script' == $type ? $data['in_footer'] : $data['media'];
						$registerFunc($handle, $data['src'], $data['deps'], $data['ver'], $lastArg);
					}

					$enqueueFunc($handle);
				}
			}
		}

	}
}
