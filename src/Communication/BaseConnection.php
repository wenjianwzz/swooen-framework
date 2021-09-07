<?php
namespace Swooen\Communication;

use Swooen\Container\Container;

/**
 * @author WZZ
 */
abstract class BaseConnection extends Container implements Connection {

	/**
	 * @var callable
	 */
	protected $packageCallback;

	/**
	 * @return \Swooen\Communication\Writer
	 */
	public function getWriter() {
		return $this->make(\Swooen\Communication\Writer::class);
	}

	public function setWriter(\Swooen\Communication\Writer $writer) : self {
		$this->instance(\Swooen\Communication\Writer::class, $writer);
		return $this;
	}

	public function dispatchPackage(Package $package) {
		call_user_func($this->packageCallback, $package, $this);
	}

	public function onPackage(callable $callable) {
		$this->packageCallback = $callable;
	}
}
