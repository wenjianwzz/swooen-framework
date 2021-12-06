<?php
namespace Swooen\Server\Swoole\Http;

use Swooen\Server\Swoole\SwooleBooter;
use Swooen\Server\Swoole\SwooleConnectionFactory;
use \Swoole\Http\Server;

/**
 * 
 * @author WZZ
 */
class SwooleHttpBooter extends SwooleBooter {
	
	/**
	 * @var \Swoole\Http\Server
	 */
	protected $server;
	
	protected function createServer(): self {
		$this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		echo "accept http request on {$this->host}:{$this->port}" . PHP_EOL;
		return $this;
	}
	
    public function boot(): void {
		$this->initOnRequest();
		parent::boot();
	}

	public function defaultConnectionFactory(): SwooleConnectionFactory {
		return new HttpConnectionFactory();
	}

	protected function initOnRequest() {
		$this->server->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
			$factory = $this->getConnectionFactory();
			assert($factory instanceof HttpConnectionFactory);
			$factory->onRequest($request, $response);
		});
	}
	
	/**
	 * 初始化Onclose
	 */
	protected function initOnclose() {
		$this->server->on('close', function(\Swoole\Server $server, $fd) {
			
		});
	}
	
}
