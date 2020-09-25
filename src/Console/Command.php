<?php
namespace Swooen\Console;

use Swooen\Core\Container;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand {
    
    protected static $defaultName = 'test';

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

    protected $container;
    
    public function __construct(Container $container) {
        parent::__construct($this->name);
        $this->container = $container;
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

    protected function execute(InputInterface $input, OutputInterface $output) {
        if (method_exists($this, 'handle')) {
            $this->container->instance(InputInterface::class, $input)->instance(OutputInterface::class, $output);
            try {
                $ret = $this->container->call([$this, 'handle'], compact('input', 'output'));
                return is_int($ret)?$ret:1;
            } finally {
                $this->container->unbind(InputInterface::class)->unbind(OutputInterface::class);
            }
        }
        return 1;
    }
    
	protected function getArgumentsConfig() {
		return [];
	}
    
	protected function getOptionsConfig() {
		return [];
	}
}