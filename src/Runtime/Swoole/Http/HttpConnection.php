<?php
namespace Swooen\Runtime\Swoole\Http;

use Swooen\Communication\Package\Package;
use Swooen\Runtime\Swoole\SwooleConnection;
use Swoole\Http\Response;

/**
 * @author WZZ
 */
class HttpConnection extends SwooleConnection {

    /**
     * @var Package
     */
    protected $package;

    /**
     * @var Response
     */
    protected $response;

	public function __construct(\Swoole\Server $server, HttpConnectionFactory $factory, Response $response, $fd) {
		$this->server = $server;
		$this->fd = $fd;
		$this->factory = $factory;
		$this->response = $response;
	}
	
	public function isStream() {
		return false;
	}

	public function terminate() {
		$this->response->end();
	}

	public function dispatchPackage(Package $package) {
		$this->package = $package;
	}

	public function listenPackage(callable $callable) {
		$callable($this->package, $this);
	}
}
