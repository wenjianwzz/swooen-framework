<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Server\Swoole\SwooleConnection;

/**
 * @author WZZ
 */
class RedisConnection extends SwooleConnection {

	protected $closed = false;

	public function onClientClosed() {
		parent::onClientClosed();
		$reader = $this->getReader();
		assert($reader instanceof RedisCommandReader);
		$reader->queueNil();
	}

	public function end(string $reason) {
		return $this->getWriter()->write($reason);
	}

	public function isClosed() {
		return $this->closed;
	}

	public function isStream() {
		return true;
	}

}
