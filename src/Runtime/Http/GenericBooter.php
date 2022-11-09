<?php
namespace Swooen\Runtime\Http;

use Swooen\Runtime\Booter;

/**
 * @author WZZ
 */
class GenericBooter extends Booter {

    public function boot(): void {
		// 创建连接，创建输入输出
		$this->getConnectionFactory()->capture();
	}

	protected function getConnectionFactory(): GlobalToConnectionFactory {
		return $this->app->make(\Swooen\Package\ConnectionFactory::class);
	}

	public function defaultConnectionFactory(): GlobalToConnectionFactory {
		return new GlobalToConnectionFactory();
	}

}
