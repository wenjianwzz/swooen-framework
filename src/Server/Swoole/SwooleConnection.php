<?php
namespace Swooen\Server\Swoole;

use Swooen\Communication\BaseConnection;
use Swooen\Communication\Package;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

/**
 * @author WZZ
 */
abstract class SwooleConnection extends BaseConnection {

	protected $closed = false;

	protected $server;

	protected $fd;

	protected $factory;

	/**
	 * @var Channel
	 */
	protected $packageChannel;

	public function __construct(\Swoole\Server $server, SwooleConnectionFactory $factory, $fd) {
		$this->server = $server;
		$this->fd = $fd;
		$this->factory = $factory;
		$this->packageChannel = new Channel(32);
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
		$this->packageChannel->push($package, -1);
	}
	
	public function listenPackage(callable $callable) {
		while ($package = $this->packageChannel->pop(-1)) {
			Coroutine::create($callable, $package, $this);
		}
	}
}
