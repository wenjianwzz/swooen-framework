<?php
namespace Swooen\Console\Command\Feature;

use Swooen\Handle\Route\Route;

/**
 * 自我路由，即跳过路由选择，但可以在路由执行器内被执行
 * @author WZZ
 */
interface SelfRouted {
	
	public function getRoute(): Route;
}

