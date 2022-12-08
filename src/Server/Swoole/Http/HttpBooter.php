<?php
namespace Swooen\Server\Swoole\Http;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Handle\HandleContext;
use Swooen\Server\Generic\Package\Reader;
use Swooen\Handle\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Handle\Writer\Writer;
use Swooen\Server\Generic\Package\HttpResponsePackage;
use Swooen\Server\Generic\Package\HttpWriter;
use Swoole\Http\Server;

/**
 * @author WZZ
 */
class HttpBooter extends ServerBooter {

	/**
	 * @var \Swoole\Http\Server
	 */
	protected $server;

	protected $host;

	protected $port;

	public function __construct($host, $port) {
		$this->host = $host;
		$this->port = $port;
	}

	protected function createServer(): self {
		\Swoole\Coroutine::set([
			'hook_flags'=> SWOOLE_HOOK_ALL,
		]);
		$this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		echo "accept http request on {$this->host}:{$this->port}" . PHP_EOL;
		return $this;
	}

    public function boot(Application $app): void {
		$this->createServer();
		$this->initOnWorkerStart($app);
		$this->initOnRequest($app);
		$this->initOnclose($app);
		$this->server->start();
	}

	protected function initOnRequest(Application $app) {
		$dispatcher = $this->createDispatcher($app);
		$reader = $this->createReader($app);
		$this->server->on('request', function (\Swoole\Http\Request $request, 
				\Swoole\Http\Response $response) use ($app, $dispatcher, $reader) {
			$writer = $this->createHttpWriter($app, $response);
			$package = $reader->package(SwooleHttpRequestPacker::packRequest($request));
			$context = $this->createContext($app);
			try {
				$dispatcher->dispatch($context, $package, $writer);
			} catch (\Throwable $t) {
				echo 'Uncaught Exception' . $t->getTraceAsString() . PHP_EOL;
				if ($app->has(LoggerInterface::class)) {
					try {
						$logger = $app->make(LoggerInterface::class);
						assert($logger instanceof LoggerInterface);
						$logger->emergency($t);
					} catch (\Throwable $t) {}
				}
				if ($writer->writable()) {
					$package = new HttpResponsePackage('Uncaught Exception');
					$package->setHttpStatusCode(500);
					$writer->end($package);
				}
			}
		});
	}

	/**
	 * 初始化OnWorkerStart
	 */
	protected function initOnWorkerStart() {
		$this->server->on('WorkerStart', function(\Swoole\Server $server, $workerId) {
			echo 'worker['. $workerId .'] started' . PHP_EOL;
		});
	}
	
	/**
	 * 初始化Onclose
	 */
	protected function initOnclose() {
		$this->server->on('close', function(\Swoole\Server $server, $fd) {
			echo $fd.' closed'. PHP_EOL;
		});
	}

	public function createDispatcher(Application $app): PackageDispatcher {
		return $app->make(PackageDispatcher::class);	
	}

	public function createContext(Application $app): HandleContext {
		return $app->make(HandleContext::class);	
	}

	public function createReader(Application $app): Reader {
		return new Reader();	
	}

	public function createHttpWriter(Application $app, \Swoole\Http\Response $response): Writer {
		return new SwooleHttpWriter($response);	
	}

}
