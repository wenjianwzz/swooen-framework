<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Handle\HandleContext;
use Swooen\Server\Generic\Package\Reader;
use Swooen\Handle\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Handle\Writer\Writer;
use Swooen\Server\Generic\Package\HttpResponsePackage;
use Swooen\Server\Swoole\Http\HttpBooter;
use Swoole\WebSocket\Server;

/**
 * @author WZZ
 */
class WebSocketBooter extends HttpBooter {

	protected function createServer(): self {
		\Swoole\Coroutine::set([
			'hook_flags'=> SWOOLE_HOOK_ALL,
		]);
		$this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		echo "accept http/websocket request on {$this->host}: {$this->port}" . PHP_EOL;
		return $this;
	}

    public function boot(Application $app): void {
		$this->createServer();
		$this->initOnWorkerStart($app);
		$this->initOnRequest($app);
		$this->initOnclose($app);
		$this->server->start();
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
