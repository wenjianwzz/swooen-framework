<?php
namespace Swooen\Communication;

use Swooen\Container\Container;

/**
 * @author WZZ
 */
abstract class BaseConnection extends Container implements Connection {

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
}
