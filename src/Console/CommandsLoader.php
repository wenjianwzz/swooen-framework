<?php
namespace Swooen\Console;

use Swooen\Application;
use Swooen\Console\Command\CommandWrap;
use Swooen\Console\Command\Feature\HandlableCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

class CommandsLoader {

    /**
     * [路由 描述 参数 选项 路由参数]
     * 参数: string $name, int $mode = null, string $description = '', $default = null
     * 选项: string $name, $shortcut = null, int $mode = null, string $description = '', $default = null
     * @var array
     */
    protected $commands = [
    ];

    public function boot(Application $app, ConsoleApplication $console, CommandDispatchAgent $agent) {
        foreach ($this->commands as $def) {
            $command = $def;
            if (is_string($def)) {
                $command = $app->make($def);
            }
            if (!$command instanceof HandlableCommand) {
                $command = new CommandWrap($command);
            }
            assert($command instanceof HandlableCommand);
            assert($command instanceof Command);
            $command->setDispatchAgent($agent);
            $console->add($command);
        }
    }
}
