<?php
namespace Swooen\Server\Generic;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Handle\HandleContext;
use Swooen\Server\Generic\Package\Reader;
use Swooen\Server\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Handle\Writer\StdoutWriter;
use Swooen\Handle\Writer\Writer;
use Swooen\Server\Generic\Package\HttpResponsePackage;
use Swooen\Server\Generic\Package\HttpWriter;

/**
 * @author WZZ
 */
class GenericBooter extends ServerBooter {

    public function boot(Application $app): void {
		$reader = $this->createReader($app);
		$writer = $this->createWriter($app);
		$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
		$package = $reader->package($request);
		$dispatcher = $this->createDispatcher($app);
		$context = $this->createContext($app);
		try {
			$dispatcher->dispatch($context, $package, $writer);
		} catch (\Throwable $t) {
			if ($app->has(LoggerInterface::class)) {
				try {
					$logger = $app->make(LoggerInterface::class);
					assert($logger instanceof LoggerInterface);
					$logger->emergency($t);
				} catch (\Throwable $t) {}
			}
			$package = new HttpResponsePackage('Uncaught Exception');
			$package->setHttpStatusCode(500);
			$writer->send($package);
		}
	}

	public function createDispatcher(Application $app): PackageDispatcher {
		return $app->make(PackageDispatcher::class);	
	}

	public function createContext(Application $app): HandleContext {
		return $app->make(HandleContext::class);	
	}

	public function createReader(Application $app): Reader {
		return new Reader();	
	}

	public function createWriter(Application $app): Writer {
		return new HttpWriter();	
	}

}
