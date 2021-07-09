<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\BaseConnection;

/**
 * @author WZZ
 */
class RedisConnection extends BaseConnection {

	protected $pairLeaved = false;

	public function setPairLeaved() {
		$this->pairLeaved = true;
		$reader = $this->getReader();
		assert($reader instanceof RedisCommandReader);
		$reader->setClosed(true);
	}

	public function end(string $reason) {
		return $this->getWriter()->write($reason);
	}

	public function isEnd() {
		return $this->closed;
	}

	public function isStream() {
		return true;
	}

}
