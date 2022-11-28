<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Console\Command\Feature\HandlableCommand;
use Swooen\Handle\PackageDispatcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 命令执行器
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
		$this->dispatcher->dispatch($context, $command->getPackage($input), $writer);
    }
}
