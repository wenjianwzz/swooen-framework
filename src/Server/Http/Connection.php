<?php
namespace Swooen\Server\Http;

use Swooen\Communication\BaseConnection;
use Swooen\Server\Http\Parser\HttpParser;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class Connection extends BaseConnection {

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
