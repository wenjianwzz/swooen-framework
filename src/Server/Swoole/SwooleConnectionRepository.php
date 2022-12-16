<?php
namespace Swooen\Server\Swoole;

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
	 * 终止Connection的时候，通知将自身移除
	 */
	public function remove(SwooleConnection $connection) {
		$fd = $connection->getFd();
		if (isset($this->connections[$fd])) {
			unset($this->connections[$fd]);
		}
	}

	public function onClose($fd) {
		if (isset($this->connections[$fd])) {
			$connection = $this->connections[$fd];
			unset($this->connections[$fd]);
			$connection->onClientClosed();
			unset($connection);
		}
	}

	public function get($fd): ?SwooleConnection {
		return isset($this->connections[$fd])?$this->connections[$fd]:null;
	}

	public function has($fd): bool {
		return isset($this->connections[$fd]);
	}

	public function add(SwooleConnection $conn): self {
		$this->connections[$conn->getFd()] = $conn->setRepository($this);
		return $this;
	}
}
