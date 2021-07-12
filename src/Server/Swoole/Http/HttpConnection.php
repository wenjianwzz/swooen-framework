<?php
namespace Swooen\Server\Swoole\Http;

use Swooen\Server\Swoole\SwooleConnection;

/**
 * @author WZZ
 */
class HttpConnection extends SwooleConnection {

	public function onClientClosed() {
		$this->closed = true;
		$reader = $this->getReader();
		assert($reader instanceof RedisCommandReader);
		$reader->setClosed(true);
	}

}
