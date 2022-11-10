<?php
namespace Swooen\Server\Generic;

use Swooen\Server\ServerBooter;

/**
 * @author WZZ
 */
class GenericBooter extends ServerBooter {

    public function boot(): void {
		// 创建连接，创建输入输出
		$this->getConnectionFactory()->capture();
	}

	protected function getConnectionFactory(): GlobalToConnectionFactory {
		return $this->app->make(\Swooen\IO\ConnectionFactory::class);
	}

	public function defaultConnectionFactory(): GlobalToConnectionFactory {
		return new GlobalToConnectionFactory();
	}

}
