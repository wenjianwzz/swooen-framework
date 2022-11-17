<?php
namespace Swooen\Console;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Handle\HandleContext;
use Swooen\Handle\Writer\StdoutWriter;
use Swooen\Handle\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\Package;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\Output;

/**
 * @author WZZ
 */
class ConsoleBooter extends ServerBooter {

    public function boot(Application $app): void {
		$dispatcher = $this->createDispatcher($app);
		$context = $this->createContext($app);
		// 启动控制台
		$console = $this->setupConsole($app, function(Package $package, Output $output) use ($app, $context, $dispatcher) {
			$writer = $this->createWriter($app, $output);
			$dispatcher->dispatch($context, $package, $writer);
		});
		$console->run();
	}

	public function createDispatcher(Application $app): PackageDispatcher {
		return $app->make(PackageDispatcher::class);	
	}

	public function createContext(Application $app): HandleContext {
		return $app->make(HandleContext::class);	
	}

	public function setupConsole(Application $app, callable $callback): ConsoleApplication {
		return $app->call(function(ConsoleApplication $console, CommandPacker $commandPacker, 
				Application $app, CommandsLoader $commandsProvider) use ($callback) {
			$app->instance(ConsoleApplication::class, $console);
			$commandsProvider->boot($app, $console, $commandPacker, $callback);
			return $console;
		});
	}

	public function createWriter(Application $app, Output $output): Writer {
		return new ConsoleWriter($output);
	}

}
