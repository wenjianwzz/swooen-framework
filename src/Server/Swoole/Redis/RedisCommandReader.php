<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\Reader;
use Swoole\Coroutine\Channel;

/**
 * @author WZZ
 */
class RedisCommandReader implements Reader {

	protected $channel;

	protected $ip;

	protected $closed;

	public function __construct($ip, $bufferSize=256) {
		$this->channel = new Channel($bufferSize);
		$this->ip = $ip;
	}

	public function queueCommand($cmd, $data) {
		$this->channel->push([$cmd, $data]);
	}

	public function hasNext() {
		// 因为是持久连接，所以应当是一直存在的，直到被关闭且缓冲被清空
		return !$this->closed && $this->channel->isEmpty();
	}

	public function next() {
		list($cmd, $data) = $this->channel->pop();
		return new RedisCommandPackage($this->ip, $cmd, $data);
	}

	/**
	 * Get the value of closed
	 */
	public function isClosed() {
		return $this->closed;
	}

	/**
	 * Set the value of closed
	 */
	public function setClosed($closed): self {
		$this->closed = $closed;
		return $this;
	}
}
