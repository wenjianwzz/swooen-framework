<?php
namespace Swooen\Console\Command;

use Swooen\Console\Command;
use Swooen\Console\Command\Feature\HandlableCommand;
use Swooen\Console\Command\Feature\HandlableCommandFeature;
use Swooen\Console\Command\Feature\SelfRouted;
use Swooen\Console\ConsolePackage;
use Swooen\Handle\Route\Route;
use Swooen\Package\Package;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\Input;
use Wenjianwzz\Tool\Util\Arr;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class CommandWrap extends SymfonyCommand implements SelfRouted, HandlableCommand {

	use HandlableCommandFeature;
	/**
	 * @var Command
	 */
	protected $command;

	public function __construct(Command $command) {
		parent::__construct($command->getName());
		$this->command = $command;
		$this->setDefinition($command->getDefinition());
	}

    public function getRoute(): Route {
		return new Route('', [[$this->command, 'handle']]);
	}

	/**
	 * Get the value of command
	 */
	public function getCommand(): Command {
		return $this->command;
	}

	/**
	 * Set the value of command
	 */
	public function setCommand(Command $command): self {
		$this->command = $command;
		return $this;
	}
}
