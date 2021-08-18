<?php
namespace Swooen\Server\Http\Reader;

use Swooen\Communication\Reader;
use Swooen\Server\Http\HttpRequestPackage;

/**
 * @author WZZ
 */
class HttpReader implements Reader {

	protected $request;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request) {
		$this->request = $request;
	}

	public function getRequest() : \Symfony\Component\HttpFoundation\Request {
		return $this->request;
	}

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {
		return !empty($this->request);
	}

	public function parseBody($contentType, $content) {
		if (stripos($contentType, 'json') !== false) {
			return json_decode($content, true);
		}
		return false;	
	}

	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {
		$body = [];
		$request = $this->request;
		$this->request = null;
		$body = $this->parseBody($request->getContentType(), $request->getContent());
		return new HttpRequestPackage($request, $body);
	}
}
