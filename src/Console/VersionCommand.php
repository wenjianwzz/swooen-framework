<?php
namespace Swooen\Console;

use Swooen\Container\Container;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersionCommand extends SymfonyCommand {
    
    /**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'version';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = '获取版本号';

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

    protected function execute(InputInterface $input, OutputInterface $output) {
        $root = \Composer\InstalledVersions::getRootPackage();
        $output->writeln("{$root['name']}: {$root['pretty_version']}");
        $version = \Composer\InstalledVersions::getPrettyVersion('wenjianwzz/swooen');
        $output->writeln("框架版本: {$version}");
        return self::SUCCESS;
    }
    
	protected function getArgumentsConfig() {
		return [];
	}
    
	protected function getOptionsConfig() {
		return [];
	}
}