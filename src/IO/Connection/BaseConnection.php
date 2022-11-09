<?php
namespace Swooen\IO\Connection;

use Swooen\Container\Container;

/**
 * @author WZZ
 */
abstract class BaseConnection extends Container implements Connection {

	/**
	 * @return \Swooen\IO\Writer
	 */
	public function getWriter() {
		return $this->make(\Swooen\IO\Writer::class);
	}

	public function setWriter(\Swooen\IO\Writer $writer) : self {
		$this->instance(\Swooen\IO\Writer::class, $writer);
		return $this;
	}
}
