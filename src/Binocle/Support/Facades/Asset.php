<?php

namespace Binocle\Support\Facades;

use \Binocle\Support\Facade as Facade;

class Asset extends Facade
{
	public static function getContainerAccessor() { return 'asset'; }
}
