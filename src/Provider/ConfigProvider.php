<?php
namespace Swooen\Provider;

use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Swooen\Config\PHPFileConfigLoader;
use Swooen\Container\Container;

class ConfigProvider extends \Swooen\Container\Provider {

	public function register(Container $container) {
		$container->singleton(ConfigRepository::class);
	}

	public function boot(ConfigRepository $config, Application $app) {
		$loader = new PHPFileConfigLoader('app', $app->configPath('app.php'));
		$loader->load($config);
		$configFiles = $config->get('app.configs', []);
		foreach($configFiles as list($name, $configFile)) {
			$loader->addConfigFile($name, $app->configPath($configFile));
		}
		$loader->load($config);
	}

	
}
