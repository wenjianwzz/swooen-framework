<?php
namespace Swooen\Runtime\Http;

use Swooen\IO\ConnectionFactory;
use Swooen\IO\Writer;
use Swooen\Runtime\Http\Parser\HttpParser;
use Swooen\Runtime\Http\Writer\HttpWriter;
use Swooen\Runtime\Http\Writer\JsonWriter;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class GlobalToConnectionFactory implements ConnectionFactory {

	protected $callback;

	/**
	 * @var HttpParser
	 */
	protected $parser;

	public function __construct(HttpParser $httpParser=null) {
		empty($httpParser) and $httpParser = new HttpParser();
		$this->parser = $httpParser;
	}
	
	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}
	
	public function capture() {
		$connection = $this->createConnection();
		$connection->setWriter($this->createWriter());
		$package = $this->parser->package(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
		$connection->dispatchPackage($package);
		($this->callback)($connection);
	}

	/**
	 * @return HttpWriter
	 */
	public function createWriter() {
		return new JsonWriter();
	}

	/**
	 * @return Connection
	 */
	public function createConnection() {
		return new Connection();
	}
}
