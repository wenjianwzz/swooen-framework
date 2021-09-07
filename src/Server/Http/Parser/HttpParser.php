<?php
namespace Swooen\Server\Http\Parser;

use Swooen\Server\Http\HttpRequestPackage;
use Swooen\Communication\Package;

/**
 * @author WZZ
 */
class HttpParser {

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
	public function package(\Symfony\Component\HttpFoundation\Request $request) {
		$body = [];
		$body = $this->parseBody($request->getContentType(), $request->getContent());
		return new HttpRequestPackage($request, $body);
	}
}
