<?php
namespace Swooen\Console\Command\Feature;

use Swooen\Console\CommandDispatchAgent;
use Symfony\Component\Console\Command\Command as CommandCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author WZZ
 */
interface HandlableCommand {

	public function getDispatchAgent(): ?CommandDispatchAgent;

	public function setDispatchAgent(CommandDispatchAgent $handler);

}

trait HandlableCommandFeature {

	/**
	 * @var CommandDispatchAgent
	 */
	protected $agent;

    protected function execute(InputInterface $input, OutputInterface $output) {
		$this->agent->execute($input, $output, $this);
		return CommandCommand::SUCCESS;
    }

	/**
	 * Get the value of handler
	 */
	public function getDispatchAgent(): ?CommandDispatchAgent {
		return $this->agent;
	}

	/**
	 * Set the value of handler
	 */
	public function setDispatchAgent(CommandDispatchAgent $agent): self {
		$this->agent = $agent;
		return $this;
	}
}
