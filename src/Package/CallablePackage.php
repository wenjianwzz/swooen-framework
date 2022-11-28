<?php
namespace Swooen\Package;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\DataArrayFeature;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\MetasFeature;
use Swooen\Package\Package;

/**
 * @author WZZ
 */
class CallablePackage implements Package, DataArray, Metas {
	use DataArrayFeature, MetasFeature;

	/**
	 * @var callable
	 */
	protected $callable;

	public function __construct(callable $callable, $metas, $dataArr) {
		$this->dataArr = $dataArr;
		$this->metas = $metas;
		$this->callable = $callable;
	}

	/**
	 * @return callable
	 */
	public function getCallable() {
		return $this->callable;
	}
	
	public function setCallable(callable $callable): self {
		$this->callable = $callable;
		return $this;
	}
}
