<?php
namespace Swooen\Runtime\Http;

use Swooen\IO\BaseConnection;
use Swooen\Package\Package;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class Connection extends BaseConnection {

	protected $package;

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

	public function dispatchPackage(Package $package) {
		$this->package = $package;
	}

	public function listenPackage(callable $callable) {
		$callable($this->package, $this);
	}

}
