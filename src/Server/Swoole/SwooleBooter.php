<?php
namespace Swooen\Server\Swoole;

use Swooen\Application;
use Swooen\Server\Booter;

/**
 * @author WZZ
 */
abstract class SwooleBooter extends Booter {
	
	/**
	 * @var \Swoole\Server
	 */
	protected $server;

	protected $host;

	protected $port;

	public function __construct(Application $application, $host, $port) {
		parent::__construct($application);
		$this->host = $host;
		$this->port = $port;
		$this->createServer();
	}

	/**
	 * 创建服务
	 */
	abstract protected function createServer(): self;

	/**
	 * 设置Swoole Server参数
	 */
	public function setSetting($setting) {
		$this->server->set($setting);
	}

    public function boot(): void {
		\Swoole\Coroutine::set([
			'hook_flags'=> SWOOLE_HOOK_ALL,
		]);
		if (!$this->app->has(\Swooen\Communication\ConnectionFactory::class)) {
			$this->withConnectionFactory($this->defaultConnectionFactory());
		}
		$this->initOnWorkerStart();
		$this->initOnclose();
		$this->server->start();
	}

	protected function getConnectionFactory(): SwooleConnectionFactory {
		return $this->app->make(\Swooen\Communication\ConnectionFactory::class);
	}

	abstract public function defaultConnectionFactory(): SwooleConnectionFactory;

	/**
	 * 初始化OnWorkerStart
	 */
	protected function initOnWorkerStart() {
		$this->server->on('WorkerStart', function(\Swoole\Server $server, $workerId) {
			$this->getConnectionFactory()->setServer($server);
			$this->app->run($this);
			echo 'worker['. $workerId .'] started' . PHP_EOL;
		});
	}
	/**
	 * 初始化OnWorkerStart
	 */
	protected function initOnclose() {
		$this->server->on('close', function(\Swoole\Server $server, $fd) {
			$this->getConnectionFactory()->onClose($server, $fd);
		});
	}
}
