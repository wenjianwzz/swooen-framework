<?php
namespace Swooen\Http;

use Swooen\Core\Container;
use Swooen\Core\Provider;

/**
 * 会在进程创建时初始化，在每个请求中注册一次。
 */
class RequestContextProvider extends Provider {
	
	public function register(Container $container) {
	}
}
