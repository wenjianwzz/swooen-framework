<?php
namespace Swooen\Server\Generic;

use Swooen\Application;
use Swooen\Handle\HandleContext;
use Swooen\Server\Generic\Package\Reader;
use Swooen\Server\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Server\Writer\StdoutWriter;
use Swooen\Server\Writer\Writer;

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
		assert($dispatcher instanceof PackageDispatcher);
		$dispatcher->dispatch($context, $package, $writer);
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
		return new StdoutWriter();	
	}

}
