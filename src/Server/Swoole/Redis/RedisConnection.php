<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\BaseConnection;

/**
 * @author WZZ
 */
class RedisConnection extends BaseConnection {

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
