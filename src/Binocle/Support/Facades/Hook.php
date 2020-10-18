<?php

namespace Binocle\Support\Facades;

class Hook extends \Binocle\Support\Facade
{
	protected static function getContainerAccessor() {
		return 'hook';
	}
}
