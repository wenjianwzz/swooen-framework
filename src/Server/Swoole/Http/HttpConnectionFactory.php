<?php
namespace Swooen\Server\Http;

use Swooen\Server\Http\Reader\HttpReader;
use Swooen\Server\Swoole\Http\HttpConnection;
use Swooen\Server\Swoole\Http\Writer\JsonWriter;
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
		$this->initOnRequest();
		$this->initOnClose();
	}
	
	protected function initOnRequest() {
		$this->server->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
			$connection = $this->createConnection($request, $response);
			($this->callback)($connection);
		});
	}

	/**
	 * @return HttpReader
	 */
	public function createReader(\Swoole\Http\Request $sreq) {
		$uri = $sreq->server['request_uri'].(isset($sreq->server['query_string'])?'?'.$sreq->server['query_string']:'');
		$method = $sreq->server['request_method'];
		$parameters = $sreq->post ?: [];
		$cookies = $sreq->cookie ?: [];
		$files = $sreq->files ?: [];
		$server = static::_server($sreq);
		$content = $sreq->rawcontent();
		return new HttpReader(\Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content));
	}

	/**
	 * @return JsonWriter
	 */
	public function createWriter(\Swoole\Http\Response $response) {
		return new JsonWriter($response);
	}

	/**
	 * @return HttpConnection
	 */
	public function createConnection(\Swoole\Http\Request $sreq, \Swoole\Http\Response $response) {
		$connection = new HttpConnection($this->server, $this, $sreq->fd);
		$connection->instance(Reader::class, $this->createReader($sreq));
		$connection->instance(Writer::class, $this->createWriter($response));
		return $connection;
	}

	protected static function _server(\Swoole\Http\Request $request) {
		$ret = [];
		foreach ($request->header as $key=>$val) {
			$key = strtoupper(str_replace('-', '_', $key));
			$ret['HTTP_'.$key] = $val;
			$ret[$key] = $val;
		}
		foreach ($request->server as $key=>$val) {
			$key = strtoupper(str_replace('-', '_', $key));
			$ret[$key] = $val;
		}
		return $ret;
	}
}
