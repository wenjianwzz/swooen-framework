<?php
namespace Swooen\Server\Http;

use Swooen\Communication\Reader;
use Swooen\Server\Http\Parser\ParserInterface;

/**
 * @author WZZ
 */
class HttpReader implements Reader {

	protected $packageGot = 0;

	/**
	 * @var ParserInterface[]
	 */
	protected $contentParsers = [];

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {
		return $this->packageGot++ <= 0;
	}

	public function registerContentParser(ParserInterface ...$parser) {
		array_unshift($this->contentParsers, ...$parser);
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
