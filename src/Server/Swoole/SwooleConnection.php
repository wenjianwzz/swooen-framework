<?php
namespace Swooen\Server\Swoole;

use Swooen\Communication\BaseConnection;

/**
 * @author WZZ
 */
class SwooleConnection extends BaseConnection {

	protected $closed = false;

	protected $server;

	protected $fd;

	protected $factory;

	public function __construct(\Swoole\Server $server, SwooleConnectionFactory $factory, $fd) {
		$this->server = $server;
		$this->fd = $fd;
		$this->factory = $factory;
	}

	/**
	 * 当客户端关闭连接，由工厂通知
	 */
	public function onClientClosed() {
		$this->closed = true;
	}

	public function isClosed() {
		return $this->closed;
	}

	public function isStream() {
		return true;
	}

	public function terminate() {
		$this->server->close($this->fd);
		$this->factory->removeConnection($this);
	}

	public function getFd() {
		return $this->fd;
	}

}
