<?php
namespace Swooen\Console;

use Swooen\Container\Container;

class CommandsProvider extends \Swooen\Container\Provider {

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
