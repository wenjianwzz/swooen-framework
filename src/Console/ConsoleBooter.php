<?php
namespace Swooen\Console;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Console\Command\VersionCommand;
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
		$handler = new CommandHandler($app, $this, $dispatcher);
		// 启动控制台
		$console = $this->setupConsole($app, $handler);
		$console->run();
	}

	public function createDispatcher(Application $app): PackageDispatcher {
		return $app->make(PackageDispatcher::class);	
	}

	public function createContext(Application $app): HandleContext {
		return $app->make(HandleContext::class);	
	}

	public function setupConsole(Application $app, CommandHandler $handler): ConsoleApplication {
		return $app->call(function(ConsoleApplication $console, Application $app, 
					CommandsLoader $commandsProvider) use ($handler) {
			$commandsProvider->boot($app, $console, $handler);
			$console->add(new VersionCommand());
			return $console;
		});
	}

	public function createWriter(Application $app, Output $output): Writer {
		return new ConsoleWriter($output);
	}

}
