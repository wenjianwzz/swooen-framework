<?php
namespace Swooen\Console;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\DataArrayFeature;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\MetasFeature;
use Swooen\Package\Features\Routeable;
use Swooen\Package\Features\RouteableFeature;
use Swooen\Package\Package;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class ConsolePackage implements Package, DataArray, Metas, Routeable {
	use DataArrayFeature, MetasFeature, RouteableFeature;

	public function __construct($routePath, $options, $arguments) {
		$this->dataArr = $arguments;
		$this->metas = $options;
		$this->routePath = $routePath;
	}
	
}
