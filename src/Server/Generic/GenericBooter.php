<?php
namespace Swooen\Server\Generic;

use Swooen\Package\Package;
use Swooen\Runtime\Http\HttpRequestPackage;
use Swooen\Server\Generic\Package\Reader;
use Swooen\Server\PackageDispatcher;
use Swooen\Server\ServerBooter;
use Swooen\Server\Writer\StdoutWriter;
use Swooen\Server\Writer\Writer;

/**
 * @author WZZ
 */
class GenericBooter extends ServerBooter {

    public function boot(): void {
		$reader = $this->createReader();
		$writer = $this->createWriter();
		$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
		$package = $reader->package($request);
		$dispatcher = $this->application->make(PackageDispatcher::class);
		assert($dispatcher instanceof PackageDispatcher);
		$dispatcher->dispatch($package, $writer);
	}

	public function createReader(): Reader {
		return new Reader();	
	}

	public function createWriter(): Writer {
		return new StdoutWriter();	
	}

}
