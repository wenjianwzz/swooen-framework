<?php
namespace Swooen\Console;

use Illuminate\Console\Command;
use Swooen\Core\Container;
use Symfony\Component\Console\Input\InputArgument;

class CommandsProvider extends \Swooen\Core\Provider {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    public function register(Container $container) {
        $container->singleton(\Symfony\Component\Console\Application::class);
    }

    public function boot(\Symfony\Component\Console\Application $console, Container $container) {
        foreach ($this->commands as $command) {
            $console->add($container->make($command));
        }
    }
}
