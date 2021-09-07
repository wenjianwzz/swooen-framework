<?php
namespace Swooen\Server\Swoole;

use Swooen\Communication\BaseConnection;
use Swooen\Communication\Package;

/**
 * @author WZZ
 */
abstract class SwooleConnection extends BaseConnection {

	protected $closed = false;

	protected $server;

	protected $fd;

	protected $factory;

	/**
	 * @var callable
	 */
	protected $packageCallback;

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

	public function dispatchPackage(Package $package) {
		call_user_func($this->packageCallback, $package,);
	}

	public function onPackage(callable $callable) {
		$this->packageCallback = $callable;
	}
}
