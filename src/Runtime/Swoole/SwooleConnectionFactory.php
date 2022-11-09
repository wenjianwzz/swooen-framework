<?php
namespace Swooen\Runtime\Swoole;

use Swooen\Package\ConnectionFactory;
use Swooen\Exception\Handler;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class SwooleConnectionFactory implements ConnectionFactory {

	/**
	 * @var \Swoole\Server
	 */
	protected $server;

	protected $callback;

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
