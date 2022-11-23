<?php
namespace Swooen\Console\Command\Feature;

use Swooen\Console\Command;
use Swooen\Console\CommandHandler;
use Swooen\Console\ConsolePackage;
use Swooen\Package\Package;
use Symfony\Component\Console\Command\Command as CommandCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wenjianwzz\Tool\Util\Arr;

/**
 * @author WZZ
 */
interface HandlableCommand {

	public function getHandler(): ?CommandHandler;

	public function setHandler(CommandHandler $handler);

	public function getPackage(Input $input): Package;
}

trait HandlableCommandFeature {

	/**
	 * @var CommandHandler
	 */
	protected $handler;

    protected function execute(InputInterface $input, OutputInterface $output) {
		$this->handler->execute($input, $output, $this);
		return CommandCommand::SUCCESS;
    }

	/**
	 * Get the value of handler
	 */
	public function getHandler(): ?CommandHandler {
		return $this->handler;
	}

	/**
	 * Set the value of handler
	 */
	public function setHandler(CommandHandler $handler): self {
		$this->handler = $handler;
		return $this;
	}

	public function getPackage(Input $input): Package {
		$arguments = $input->getArguments();
        $package = new ConsolePackage(Arr::pull($arguments, 'command'), $input->getOptions(), $arguments);
        return $package;
	}
}
