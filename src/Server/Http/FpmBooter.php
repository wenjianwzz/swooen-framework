<?php
namespace Swooen\Server\Http;

use Swooen\Application;
use Swooen\Server\Booter;

/**
 * @author WZZ
 */
class FpmBooter extends Booter {

	public function __construct(Application $application) {
		parent::__construct($application);
	}

    public function boot(): void {
		if (!$this->app->has(\Swooen\Communication\ConnectionFactory::class)) {
			$this->withConnectionFactory($this->defaultConnectionFactory());
		}
		$this->app->run($this);
		$this->getConnectionFactory()->capture();
	}

	protected function getConnectionFactory(): GlobalToConnectionFactory {
		return $this->app->make(\Swooen\Communication\ConnectionFactory::class);
	}

	public function defaultConnectionFactory(): GlobalToConnectionFactory {
		return new GlobalToConnectionFactory();
	}

}
