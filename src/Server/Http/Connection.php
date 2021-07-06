<?php
namespace Swooen\Server\Http;

use Swooen\Communication\Connection as ConnectionInterface;
use Swooen\Communication\Package;
use Swooen\Container\Container;
use Swooen\Server\Http\Parser\ParserInterface;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class Connection extends Container implements ConnectionInterface {

	protected $packageGot = 0;

	/**
	 * @var ParserInterface[]
	 */
	protected $contentParsers = [];
	
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

	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function canWrite() {
		return true;
	}

	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function push(Package $package) {
		echo $package->raw();
		return true;
	}

	public function write(string $content) {
		echo $content;
	}

	/**
	 * 终止连接，并向对方发送终止原因
	 */
	public function end(string $reason) {}

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isEnd() {}

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream() {}

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {
		return $this->packageGot++ <= 0;
	}

}
