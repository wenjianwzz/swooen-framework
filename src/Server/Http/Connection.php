<?php
namespace Swooen\Server\Http;

use Swooen\Communication\Connection as ConnectionInterface;
use Swooen\Communication\Package;
use Swooen\Container\Container;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class Connection extends Container implements ConnectionInterface {


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

	public function terminate() {
	}

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isClosed() {}

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream() {
		return false;
	}

}
