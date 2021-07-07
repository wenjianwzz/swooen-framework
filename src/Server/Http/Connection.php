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

	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function push(Package $package) {
		return $this->getWriter()->write($package->raw());
	}

	/**
	 * 终止连接，并向对方发送终止原因
	 */
	public function end(string $reason) {
		return $this->getWriter()->write($reason);
	}

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isEnd() {}

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream() {
		return false;
	}

}
