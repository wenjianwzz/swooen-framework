<?php
namespace Swooen\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand {
    
    /**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = '';
    
    public function __construct() {
        parent::__construct($this->name);
    }

    protected function configure() {
        $this->setDescription($this->description);
        foreach ($this->getArgumentsConfig() as $vars) {
            $this->addArgument(...$vars);
        }
        foreach ($this->getOptionsConfig() as $vars) {
            $this->addOption(...$vars);
        }
    }

	protected function getArgumentsConfig() {
		return [];
	}
    
	protected function getOptionsConfig() {
		return [];
	}
}