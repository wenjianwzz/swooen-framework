<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Server\Swoole\SwooleConnection;

/**
 * @author WZZ
 */
class RedisConnection extends SwooleConnection {

	public function onClientClosed() {
		parent::onClientClosed();
		$reader = $this->getReader();
		assert($reader instanceof RedisCommandReader);
		$reader->queueNil();
	}

}
