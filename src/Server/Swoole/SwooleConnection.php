<?php
namespace Swooen\Server\Swoole;

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
	 * @var Channel
	 */
	protected $packageChannel;

	/**
	 * @var SwooleConnectionRepository
	 */
	protected $repository;

	public function __construct(\Swoole\Server $server, $fd) {
		$this->server = $server;
		$this->fd = $fd;
		$this->packageChannel = new Channel(32);
	}

	/**
	 * 当客户端关闭连接，由工厂通知
	 */
	public function onClientClosed() {
		$this->closed = true;
		if ($this->repository) {
			$this->repository->remove($this);
		}
		$this->packageChannel->push(null);
	}

	public function isClosed() {
		return $this->closed;
	}

	public function terminate() {
		if ($this->server->exists($this->fd)) {
			$this->server->close($this->fd);
		}
		if ($this->repository) {
			$this->repository->remove($this);
		}
	}

	public function getFd() {
		return $this->fd;
	}

	public function queuePackage(Package $package) {
		$this->packageChannel->push($package, -1);
	}

	
	public function startLoop() {
		echo time(). ' connection[fd='.$this->fd.'] loop started'. PHP_EOL;
		while ($package = $this->packageChannel->pop(-1)) {
			if ($package instanceof Package) {
				$this->handlePackage($package);
			}
		}
		echo time(). ' connection[fd='.$this->fd.'] loop end'. PHP_EOL;
	}

	abstract function handlePackage(Package $package);

	/**
	 * Set the value of repository
	 */
	public function setRepository(SwooleConnectionRepository $repository): self {
		$this->repository = $repository;
		return $this;
	}
}
