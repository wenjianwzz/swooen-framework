<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Server\Swoole\Http\SwooleHttpBooter;
use Swooen\Server\Swoole\SwooleBooter;
use Swooen\Server\Swoole\SwooleConnectionFactory;
use \Swoole\WebSocket\Server;

/**
 * 
 * @author WZZ
 */
class WebSocketBooter extends SwooleHttpBooter {
	
	/**
	 * @var \Swoole\Http\Server
	 */
	protected $server;
	
	protected function createServer(): self {
		$this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		echo "accept websocket on {$this->host}:{$this->port}" . PHP_EOL;
		return $this;
	}
	
    public function boot(): void {
		$this->initSocket();
		parent::boot();
	}

	public function defaultConnectionFactory(): SwooleConnectionFactory {
		return new WebSocketConnectionFactory();
	}

	protected function initSocket() {
		$this->server->on('open', function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq) {
			$factory = $this->getConnectionFactory();
			assert($factory instanceof WebSocketConnectionFactory);
			$factory->onConnected($server, $sreq);
		});
		$this->server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
			$factory = $this->getConnectionFactory();
			assert($factory instanceof WebSocketConnectionFactory);
			$factory->onFrame($server, $frame);
		});
	}
		
}
