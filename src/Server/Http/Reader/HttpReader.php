<?php
namespace Swooen\Server\Http\Reader;

use Swooen\Communication\Reader;
use Swooen\Server\Http\HttpRequestPackage;

/**
 * @author WZZ
 */
class HttpReader implements Reader {

	protected $packageGot = 0;

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {
		return $this->packageGot++ <= 0;
	}

	public function parseBody($contentType, $content) {
		if (stripos($contentType, 'json') !== false) {
			return json_decode($content, true);
		}
		return [];	
	}

	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {
		$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
		$body = [];
		$body = $this->parseBody($request->getContentType(), $request->getContent());
		return new HttpRequestPackage($request, $body);
	}
}
