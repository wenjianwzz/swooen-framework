<?php
namespace Swooen\Package\Connection;

use Swooen\Container\Container;

/**
 * @author WZZ
 */
abstract class BaseConnection extends Container implements Connection {

	/**
	 * @return \Swooen\Package\Writer
	 */
	public function getWriter() {
		return $this->make(\Swooen\Package\Writer::class);
	}

	public function setWriter(\Swooen\Package\Writer $writer) : self {
		$this->instance(\Swooen\Package\Writer::class, $writer);
		return $this;
	}
}
