<?php
namespace Swooen\Server\Swoole;

use Swooen\Handle\PackageDispatcher;
use Swooen\IO\Connection\BaseConnection as ConnectionBaseConnection;
use Swooen\Package\Package;
use Swoole\Coroutine\Channel;

/**
 * @author WZZ
 */
abstract class SwooleConnection {

	protected $closed = false;

	protected $server;

	protected $fd;

	/**
	 * @var PackageDispatcher
	 */
	protected $dispatcher;

	public function __construct(\Swoole\Server $server, $fd, PackageDispatcher $packageDispatcher) {
		$this->server = $server;
		$this->fd = $fd;
		$this->dispatcher = $packageDispatcher;
	}

	/**
	 * 当客户端关闭连接，由工厂通知
	 */
	public function onClientClosed() {
		$this->closed = true;
		$this->packageChannel->push(null);
	}

	public function isClosed() {
		return $this->closed;
	}

	public function terminate() {
		$this->server->close($this->fd);
		$this->factory->removeConnection($this);
	}

	public function getFd() {
		return $this->fd;
	}

	public function dispatchPackage(Package $package) {
		$this->packageChannel->push($package, -1);
	}
	
	public function listenPackage(callable $callable) {
		while ($package = $this->packageChannel->pop(-1)) {
			$callable($package, $this);
		}
	}
}
