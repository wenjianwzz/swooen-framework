<?php
namespace Swooen\Server\Http;

use Swooen\Communication\ConnectionFactory;
use Swooen\Communication\Reader;
use Swooen\Communication\Writer;
use Swooen\Server\Http\Reader\HttpReader;
use Swooen\Server\Http\Writer\HttpWriter;
use Swooen\Server\Http\Writer\JsonWriter;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class GlobalToConnectionFactory implements ConnectionFactory {

	protected $callback;
	
	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}
	
	public function start() {
		$connection = $this->createConnection();
		$reader = $this->createReader();
		$connection->instance(Writer::class, $this->createWriter());
		$connection->instance(Reader::class, $reader);
		($this->callback)($connection);
	}

	/**
	 * @return HttpReader
	 */
	public function createReader() {
		return new HttpReader(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
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
