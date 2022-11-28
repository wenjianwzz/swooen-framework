<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Console\Command\Feature\HandlableCommand;
use Swooen\Handle\PackageDispatcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wenjianwzz\Tool\Util\Arr;

/**
 * 命令执行器
 * @author WZZ
 */
class CommandDispatchAgent {

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

		$arguments = $input->getArguments();
        $package = new ConsolePackage([$command, 'handle'], Arr::pull($arguments, 'command'), $input->getOptions(), $arguments);

		$this->dispatcher->dispatch($context, $package, $writer);
    }
}
