<?php
namespace Swooen\Console\Commands;

use Swooen\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Providers\MiddlewareProvider;
use Swooen\Container\Container;
use Swooen\Http\Routes\Router;
use Swooen\Http\ServePipeline;
use Swooen\Http\ServeStatic;

class StartServer extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'server:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '启动服务器';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(InputInterface $input, \Swooen\Application $rootApp, OutputInterface $output) {
		$host = $input->getArgument('host');
		$port = $input->getArgument('port');
		$public = $input->getArgument('public');
		$workerNum = $input->getArgument('worker');
		$output->writeln('主进程启动, pid= ' . getmypid());
		//多进程管理模块                                                                                              
		$pool = new \Swoole\Process\Pool($workerNum);
		//让每个OnWorkerStart回调都自动创建一个协程
		$pool->set(['enable_coroutine' => true]);
		$pool->on("workerStart", function ($pool, $id) use ($rootApp, $host, $port, $public, $output) {
			$output->writeln("worker[{$id}] start, pid=" . getmypid());
			\Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL);
			// 加载Lumen APP
			$app = require $rootApp->basePath('/bootstrap/app.php');
			assert($app instanceof \Swooen\Application);
			$app->instance(\Swooen\Http\RequestContextProvider::class, $app->make(\App\Providers\RequestContextProvider::class));
			$server = new \Swoole\Coroutine\Http\Server($host, $port, false, true);
			// 设置
			\Swooen\Http\Request::setTrustedProxies(['127.0.0.1'], \Swooen\Http\Request::HEADER_X_FORWARDED_ALL);
			$server->handle('/', function (\Swoole\Http\Request $req, \Swoole\Http\Response $resp) use ($app, $public) {
				$container = $this->createContainer($app, $req, $resp);
				$publicDir = $app->basePath($public);
				$request = \Swooen\Http\Request::createFromSwoole($req);
				if (!ServeStatic::serve($publicDir, $request, $container->make(\Swooen\Http\Writer\Writer::class))) {
					$app->handleHttp($request, $container);
				}
				$container->destroy();
			});
			//收到15信号关闭服务
			\Swoole\Process::signal(SIGTERM, function () use ($server) {
				$server->shutdown();
			});
			$server->start();
		});
		$pool->start(); 
	}

	protected function createContainer(\Swooen\Application $app, \Swoole\Http\Request $req, \Swoole\Http\Response $resp) {
		return (new Container())->instance(\Swooen\Application::class, $app)
		->instance(\App\Core\Application::class, $app)
		->instance(\Swoole\Http\Request::class, $req)->instance(\Swoole\Http\Response::class, $resp)
		->instance(\Illuminate\Config\Repository::class, $app->make(\Illuminate\Config\Repository::class))
		->singleton(\Swooen\Http\Writer\Writer::class, \Swooen\Http\Writer\SwooleWriter::class);
	}
	
	protected function getArgumentsConfig() {
		return [
			['host', InputArgument::REQUIRED, '监听地址'],
			['port', InputArgument::REQUIRED, '监听端口'],
			['public', InputArgument::OPTIONAL, '公开目录', 'public'],
			['worker', InputArgument::OPTIONAL, '进程数目', function_exists('swoole_cpu_num')?swoole_cpu_num():1],
		];
	}

}