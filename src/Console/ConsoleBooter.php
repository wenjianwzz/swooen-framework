<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Console\Command\VersionCommand;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Handle\Writer\Writer;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\Output;

/**
 * @author WZZ
 */
class ConsoleBooter extends ServerBooter {

    public function boot(Application $app): void {
		$dispatcher = $this->createDispatcher($app);
		$agent = $this->createDispatchAgent($app, $dispatcher);
		// 启动控制台
		$console = $this->setupConsole($app, $agent);
		$console->run();
	}

	public function createDispatcher(Application $app): PackageDispatcher {
		return $app->make(PackageDispatcher::class);	
	}

	public function createDispatchAgent(Application $app, PackageDispatcher $dispatcher): CommandDispatchAgent {
		return new CommandDispatchAgent($app, $this, $dispatcher);
	}

	public function createContext(Application $app): HandleContext {
		return $app->make(HandleContext::class);	
	}

	public function setupConsole(Application $app, CommandDispatchAgent $agent): ConsoleApplication {
		return $app->call(function(ConsoleApplication $console, Application $app, 
					CommandsLoader $commandsProvider) use ($agent) {
			$commandsProvider->boot($app, $console, $agent);
			$console->add(new VersionCommand());
			return $console;
		});
	}

	public function createWriter(Application $app, Output $output): Writer {
		return new ConsoleWriter($output);
	}

}
