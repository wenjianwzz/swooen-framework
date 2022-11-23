<?php
namespace Swooen\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wenjianwzz\Tool\Util\Arr;

class PackagedCommand extends SymfonyCommand {

    protected $callback;

    public function __construct($name, callable $callback) {
        parent::__construct($name);
        $this->callback = $callback;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $arguments = $input->getArguments();
        $package = new ConsolePackage(Arr::pull($arguments, 'command'), $input->getOptions(), $arguments);
        ($this->callback)($package, $output);
        return 1;
    }
    
}