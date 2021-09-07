<?php
namespace Swooen\Server\Http;

use Swooen\Communication\ConnectionFactory;
use Swooen\Communication\Writer;
use Swooen\Server\Http\Parser\HttpParser;
use Swooen\Server\Http\Writer\HttpWriter;
use Swooen\Server\Http\Writer\JsonWriter;

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
	}
	
	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}
	
	public function start() {
		$connection = $this->createConnection();
		$connection->instance(Writer::class, $this->createWriter());
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
		return (new Connection())->setParser($this->parser);
	}
}
