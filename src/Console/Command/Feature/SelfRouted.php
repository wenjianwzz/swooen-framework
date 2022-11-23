<?php
namespace Swooen\Console\Command\Feature;

use Swooen\Handle\Route\Route;

/**
 * @author WZZ
 */
interface SelfRouted {
	
	public function getRoute(): Route;
}

