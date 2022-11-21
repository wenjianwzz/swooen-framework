<?php
namespace Swooen\Provider;

use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Swooen\Container\Container;

class ConfigProvider extends \Swooen\Container\Provider {

	public function register(Container $container) {
		$container->singleton(ConfigRepository::class);
	}

	public function boot(ConfigRepository $config, Application $app) {
		$configPath = $app->configPath();
		$files = scandir($configPath);
		$configs = [];
		$locals = [];
		foreach($files as $file) {
			if (preg_match('/(?<name>\w+)(?<local>\.\w+)?\.php/', $file, $match)) {
				if (isset($match['local'])) {
					$locals[$match['name']] = require $app->configPath($file);
				} else {
					$configs[$match['name']] = require $app->configPath($file);
				}
			}
		}
		foreach ($configs as $name => $item) {
			$config->set($name, $item);
		}
		foreach ($locals as $name => $item) {
			$config->set($name, $item);
		}
	}

	
}
