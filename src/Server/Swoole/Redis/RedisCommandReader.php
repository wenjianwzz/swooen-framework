<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\Reader;

/**
 * @author WZZ
 */
class RedisCommandReader implements Reader {

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {
		return $this->packageGot++ <= 0;
	}

	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {
		$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
		$body = [];
		foreach ($this->contentParsers as $parser) {
			if ($parser->accept($request->getContentType())) {
				$body = $parser->parse($request->getContent());
			}
		}
		return new HttpRequestPackage($request, $body);
	}
}
