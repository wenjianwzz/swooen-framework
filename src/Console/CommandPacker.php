<?php
namespace Swooen\Console;

use Swooen\Handle\HandleContext;
use Swooen\Package\DataPackage;
use Swooen\Package\Package;
use Symfony\Component\Console\Input\InputInterface;
use Wenjianwzz\Tool\Util\Arr;

class CommandPacker {

    public function pack(InputInterface $input): Package {
        $arguments = $input->getArguments();
        return new ConsolePackage(Arr::pull($arguments, 'command'), $input->getOptions(), $arguments);
    }
    
}