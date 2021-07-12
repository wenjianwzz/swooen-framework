<?php
namespace Swooen\Server\Swoole;

use Swooen\Communication\ConnectionFactory;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class SwooleConnectionFactory implements ConnectionFactory {

	protected $server;

	protected $callback;

	/**
	 * @var SwooleConnection[]
	 */
	protected $connections = [];

	/**
	 * 初始化onClose事件, 收到客户端终止连接的事件
	 */
	protected function initOnClose() {
		$this->server->on('close', function($server, $fd) {
			if (isset($this->connections[$fd])) {
				$this->onClose($fd);
			}
		});
	}

	/**
	 * 终止Connection的时候，通知Factory将自身移除
	 */
	public function removeConnection(SwooleConnection $connection) {
		$fd = $connection->getFd();
		unset($this->connections[$fd]);
	}

	public function onClose($fd) {
		$connection = $this->connections[$fd];
		unset($this->connections[$fd]);
		$connection->onClientClosed();
		unset($connection);
	}

	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}
	
	public function start() {
		$this->server->start();
	}


}
