<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Server\Http\Reader\HttpReader;
use Swooen\Server\Swoole\Http\HttpConnectionFactory;
use Swooen\Server\Swoole\WebSocket\Reader\JsonOnWsReader;
use Swooen\Server\Swoole\WebSocket\Reader\JsonReader;
use Swooen\Server\Swoole\WebSocket\Writer\JsonWriter;
use \Swoole\WebSocket\Server;

/**
 * 
 * @author WZZ
 */
class WsJsonConnectionFactory extends HttpConnectionFactory {
	
	/**
	 * @var \Swoole\Http\Server
	 */
	protected $server;

	public function __construct($host, $port) {
		$this->server = new Server($host, $port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		$this->initOnRequest();
		$this->initOnClose();
		$this->initSocket();
	}
	
	protected function initSocket() {
		$this->server->on('open', function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $request) {
			$connection = $this->createWsConnection($server, $request);
			$this->connections[$request->fd] = $connection;
			($this->callback)($connection);
			// 增加连接包
		});
		$this->server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
			$conn = isset($this->connections[$frame->fd])?$this->connections[$frame->fd]:null;
			if ($conn and $conn instanceof WebSocketConnection) {
				$conn->queueFrame($frame);
			}
		});
	}

	/**
	 * @return HttpConnection
	 */
	public function createWsConnection(\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq) {
		$connection = new WebSocketConnection($this->server, $this, $sreq->fd);
		$connection->instance(\Swooen\Communication\Reader::class, $this->createWsReader($server, $connection, $sreq));
		$connection->instance(\Swooen\Communication\Writer::class, $this->createWsWriter($server, $sreq->fd));
		return $connection;
	}

	/**
	 * @return JsonReader
	 */
	public function createWsReader(\Swoole\WebSocket\Server $server, WebSocketConnection $connection, \Swoole\Http\Request $sreq) {
		$uri = $sreq->server['request_uri'].(isset($sreq->server['query_string'])?'?'.$sreq->server['query_string']:'');
		$method = $sreq->server['request_method'];
		$parameters = $sreq->post ?: [];
		$cookies = $sreq->cookie ?: [];
		$files = $sreq->files ?: [];
		$serverInfo = static::_server($sreq);
		$content = $sreq->rawcontent();
		$req = \Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $serverInfo, $content);
		return new JsonOnWsReader($server, $connection, $req);
	}

	/**
	 * @return JsonWriter
	 */
	public function createWsWriter(\Swoole\WebSocket\Server $server, $fd) {
		return new JsonWriter($server, $fd);
	}
	
	/**
	 * @return HttpReader
	 */
	public function createWebSocketReader(\Swoole\Http\Request $sreq) {
		$uri = $sreq->server['request_uri'].(isset($sreq->server['query_string'])?'?'.$sreq->server['query_string']:'');
		$method = $sreq->server['request_method'];
		$parameters = $sreq->post ?: [];
		$cookies = $sreq->cookie ?: [];
		$files = $sreq->files ?: [];
		$server = static::_server($sreq);
		$content = $sreq->rawcontent();
		return new HttpReader(\Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content));
	}

}
