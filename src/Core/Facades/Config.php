<?php

namespace Binocle\Core\Facades;

use \Binocle\Support\Facade as Facade;

class Config extends Facade
{
	public static function getContainerAccessor() { return 'config'; }
}
