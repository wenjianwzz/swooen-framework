<?php
namespace Swooen\Provider;

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Wenjianwzz\Tool\Util\Arr;

class LogProvider extends \Swooen\Container\Provider {

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register(\Swooen\Container\Container $container) {
		$container->singleton(\Psr\Log\LoggerInterface::class, function(Application $app, ConfigRepository $config) {
			$logger = new Logger($app->getAppName());
			$logHandlers = $config->get('logging.handlers', []);
			foreach ($logHandlers as $logHandler) {
				$handler = $logHandler['handler'];
				switch ($handler) {
					case 'syslog':
						if ($app->has(SyslogUdpHandler::class)) {
							$handler = $app->make(SyslogUdpHandler::class);
						} else {
							$handler = new SyslogUdpHandler($logHandler['host'], $logHandler['port'], 
													LOG_USER, Logger::DEBUG, true, 
													Arr::get($logHandler, 'ident', $app->getAppName()));
						}
						$logger->pushHandler($handler);
						break;
					case 'file':
						if ($app->has(StreamHandler::class)) {
							$handler = $app->make(StreamHandler::class);
						} else {
							$handler = new StreamHandler($app->storagePath($logHandler['dir'].$app->getAppName().'-'.date('Ymd').'.log'));
						}
						$logger->pushHandler($handler);
						break;
				}
			}
			return $logger;
		});
		$container->alias(\Psr\Log\LoggerInterface::class, \Monolog\Logger::class);
	}
	
}
