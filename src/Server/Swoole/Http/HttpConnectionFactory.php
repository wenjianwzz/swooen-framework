<?php
namespace Swooen\Server\Http;

use Swooen\Server\Swoole\SwooleConnectionFactory;
use \Swoole\Http\Server;
use Swoole\IDEHelper\StubGenerators\Swoole;

/**
 * 
 * @author WZZ
 */
class HttpConnectionFactory extends SwooleConnectionFactory {
	
	/**
	 * @var \Swoole\Http\Server
	 */
	protected $server;

	public function __construct($host, $port, $mode=SWOOLE_BASE, $sockType=SWOOLE_SOCK_TCP) {
		$this->server = new Server($host, $port, $mode, $sockType);
		$this->initOnClose();
	}

	/**
	 * @return RedisCommandReader
	 */
	public function createReader($fd) {
		$info = $this->server->getClientInfo($fd);
		$ip = isset($info['remote_ip'])?$info['remote_ip']:'';
		return new RedisCommandReader($ip);
	}

	/**
	 * @return RedisWriter
	 */
	public function createWriter($fd) {
		return new RedisWriter($this->server, $fd);
	}

	/**
	 * @return RedisConnection
	 */
	public function createConnection($fd) {
		return new RedisConnection();
	}
}
