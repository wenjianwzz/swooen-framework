<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Swooen\Application;
use Swooen\Handle\ConnectionContext;
use Swooen\Server\Swoole\Http\HttpBooter;
use Swooen\Server\Swoole\Http\SwooleHttpRequestPacker;
use Swooen\Server\Swoole\SwooleConnectionRepository;
use Swooen\Server\Swoole\Http\WebSocket\WebSocketConnection;
use Swoole\WebSocket\Server;
use Swooen\Handle\PackageDispatcher;
use Swooen\Package\Package;

/**
 * @author WZZ
 */
class WebSocketBooter extends HttpBooter {

	/**
	 * @var SwooleConnectionRepository
	 */
	protected $connections;

	/**
	 * @var PackageDispatcher
	 */
	protected $dispatcher;

	/**
	 * @var Application
	 */
	protected $app;

	protected function createServer(): self {
		\Swoole\Coroutine::set([
			'hook_flags'=> SWOOLE_HOOK_ALL,
		]);
		$this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		echo "accept http/websocket request on {$this->host}: {$this->port}" . PHP_EOL;
		return $this;
	}

    public function boot(Application $app): void {
		$this->connections = new SwooleConnectionRepository();
		$this->createServer();
		$this->initOnWorkerStart($app);
		$this->initOnRequest($app);
		$this->initOnclose($app);
		$this->initSocket($app);
		$this->app = $app;
		$this->dispatcher = $this->createDispatcher($app);
		$this->server->start();
	}

	public function handle(Package $package, WebSocketConnection $webSocketConnection) {
		$writer = new WebSocketWriter($webSocketConnection);
		$context = $this->createContext($this->app);
		$this->dispatcher->dispatch($context, $package, $writer);
	}

	public function createConnectionContext(Application $app): ConnectionContext {
		return $app->make(ConnectionContext::class);	
	}

	protected function initSocket(Application $app) {
		$this->server->on('open', function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq) use ($app) {
			$conn = new WebSocketConnection($this, $this->createConnectionContext($app), $server, $sreq->fd, SwooleHttpRequestPacker::packRequest($sreq));
			$this->connections->add($conn);
			$conn->startLoop();
		});
		$this->server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
			if ($this->connections->has($frame->fd)) {
				$conn = $this->connections->get($frame->fd);
				assert($conn instanceof WebSocketConnection);
				$conn->pushFrame($frame);
			}
		});
	}


}
