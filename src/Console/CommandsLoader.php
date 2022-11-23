<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Container\Container;
use Swooen\Handle\HandleContext;
use Symfony\Component\Console\Application as ConsoleApplication;

class CommandsLoader {

    /**
     * 名称 描述 参数 选项
     * 参数: string $name, int $mode = null, string $description = '', $default = null
     * 选项: string $name, $shortcut = null, int $mode = null, string $description = '', $default = null
     * @var array
     */
    protected $commands = [
    ];

    public function boot(Application $app, ConsoleApplication $console, callable $callback) {
        foreach ($this->commands as list($name, $description, $args, $opts)) {
            $command = new PackagedCommand($name, $callback);
            $command->setDescription($description);
            foreach($args as $argDef) {
                $command->addArgument(...$argDef);
            }
            foreach($opts as $optDef) {
                $command->addOption(...$optDef);
            }
            $console->add($command);
        }
        $console->add(new VersionCommand());
    }
}
