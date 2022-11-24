<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Console\Command\Feature\HandlableCommand;
use Swooen\Console\Command\Feature\SelfRouted;
use Swooen\Handle\PackageDispatcher;
use Swooen\Handle\Route\Route;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author WZZ
 */
class CommandHandler {

	protected $booter;

	protected $dispatcher;

	protected $app;
	
	public function __construct(Application $app, ConsoleBooter $booter, PackageDispatcher $dispatcher) {
		$this->booter = $booter;
		$this->dispatcher = $dispatcher;
		$this->app = $app;
	} 

    public function execute(InputInterface $input, OutputInterface $output, HandlableCommand $command) {
		$writer = $this->booter->createWriter($this->app, $output);
		$context = $this->booter->createContext($this->app);
		$context->instance(InputInterface::class, $input);
		$context->instance(OutputInterface::class, $output);
		if ($command instanceof SelfRouted) {
			$route = $command->getRoute();
			$context->instance(Route::class, $route);
		}
		$this->dispatcher->dispatch($context, $command->getPackage($input), $writer);
    }
}
