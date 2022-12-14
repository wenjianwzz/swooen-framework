<?php
namespace Swooen\Server\Swoole;

use Swooen\IO\ConnectionFactory;

/**
 * 
 * @author WZZ
 */
class SwooleConnectionRepository {

	/**
	 * @var SwooleConnection[]
	 */
	protected $connections = [];

	/**
	 * 终止Connection的时候，通知Factory将自身移除
	 */
	public function removeConnection(SwooleConnection $connection) {
		$fd = $connection->getFd();
		if (isset($this->connections[$fd])) {
			unset($this->connections[$fd]);
		}
	}

	public function onClose(\Swoole\Server $server, $fd) {
		if (isset($this->connections[$fd])) {
			$connection = $this->connections[$fd];
			unset($this->connections[$fd]);
			$connection->onClientClosed();
			unset($connection);
		}
	}

	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}

	/**
	 * Set the value of server
	 */
	public function setServer($server): self {
		$this->server = $server;
		return $this;
	}

}
