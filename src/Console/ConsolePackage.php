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
 * @author WZZ
 */
class ConsolePackage implements Package, DataArray, Metas, Routeable {
	use DataArrayFeature, MetasFeature, RouteableFeature;

	/**
	 * @var callable
	 */
	protected $callable;

	public function __construct(callable $callable, $routePath, $options, $arguments) {
		$this->dataArr = $arguments;
		$this->metas = $options;
		$this->routePath = $routePath;
		$this->callable = $callable;
	}

	public function getCallable() {
		return $this->callable;
	}
	
	public function setCallable(callable $callable): self {
		$this->callable = $callable;
		return $this;
	}
}
