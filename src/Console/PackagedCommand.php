<?php
namespace Swooen\Console;

use Swooen\Handle\HandleContext;
use Swooen\Package\DataPackage;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackagedCommand extends SymfonyCommand {

    protected $callback;

    /**
     * @var CommandPacker
     */
    protected $commandPacker;
    
    public function __construct($name, CommandPacker $commandPacker, callable $callback) {
        parent::__construct($name);
        $this->callback = $callback;
        $this->commandPacker = $commandPacker;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        ($this->callback)($this->commandPacker->pack($input), $output);
        return 1;
    }
    
}