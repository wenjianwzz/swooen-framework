<?php
namespace Swooen\Server\Http;

use Swooen\Communication\BaseConnection;
use Swooen\Server\Http\Parser\HttpParser;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class Connection extends BaseConnection {

	/**
	 * @var HttpParser
	 */
	protected $parser;

	public function terminate() {
	}

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isClosed() {}

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream() {
		return false;
	}

	public function onPackage(callable $callable) {
		$callable($this->parser->package(\Symfony\Component\HttpFoundation\Request::createFromGlobals()));
	}

	/**
	 * Get the value of parser
	 * @return HttpParser
	 */
	public function getParser() {
		return $this->parser;
	}

	/**
	 * Set the value of parser
	 */
	public function setParser(HttpParser $parser): self {
		$this->parser = $parser;
		return $this;
	}
}
