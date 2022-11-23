<?php
namespace Swooen\Console\Command;

use Swooen\Console\Command\Feature\HandlableCommand;
use Swooen\Console\Command\Feature\HandlableCommandFeature;
use Swooen\Console\ConsolePackage;
use Swooen\Package\Features\Routeable;
use Swooen\Package\Features\RouteableFeature;
use Swooen\Package\Package;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\Input;

class PackagedCommand extends SymfonyCommand implements Routeable, HandlableCommand {

    use RouteableFeature, HandlableCommandFeature;

    /**
     * 参数$arguments: string $name, int $mode = null, string $description = '', $default = null
     * 选项$options: string $name, $shortcut = null, int $mode = null, string $description = '', $default = null
     */
    public function __construct($routePath, $description, $arguments=[], $options=[], $routeParam=[]) {
        parent::__construct(str_replace(' ', ':', $routePath));
        $this->routePath = $routePath;
        $this->setDescription($description);
        foreach($arguments as $argDef) {
            $this->addArgument(...$argDef);
        }
        foreach($options as $optDef) {
            $this->addOption(...$optDef);
        }
    }

	public function getPackage(Input $input): Package {
		$arguments = $input->getArguments();
        $package = new ConsolePackage($this->routePath, $input->getOptions(), $arguments);
        return $package;
	}
    
}