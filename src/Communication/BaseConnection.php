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

	/**
	 * @return \Swooen\Communication\Reader
	 */
	public function getReader() {
		return $this->make(\Swooen\Communication\Reader::class);
	}


}
