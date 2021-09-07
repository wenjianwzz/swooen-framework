<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Server\Swoole\Http\HttpConnectionFactory;
use Swooen\Server\Swoole\WebSocket\Package\WebSocketParser;
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

	/**
	 * @var WebSocketParser
	 */
	protected $socketParser;

	public function __construct($host, $port, WebSocketParser $socketParser=null) {
		$this->server = new Server($host, $port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		$this->initOnRequest();
		$this->initOnClose();
		$this->initSocket();
		empty($socketParser) and $socketParser = $this->createSocketParser();
		$this->setParser($this->createParser());
		$this->setSocketParser($socketParser);
	}

	
	/**
	 * @return WebSocketParser
	 */
	public function createSocketParser() {
		return new WebSocketParser();
	}

	/**
	 * @return HttpParser
	 */
	public function getSocketParser() {
		return $this->socketParser;
	}

	/**
	 * Set the value of parser
	 */
	public function setSocketParser(WebSocketParser $socketParser): self {
		$this->socketParser = $socketParser;
		return $this;
	}

	protected function initSocket() {
		$this->server->on('open', function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq) {
			$request = $this->packRequest($sreq);
			$connection = $this->createWsConnection($server, $sreq, $request);
			$this->connections[$sreq->fd] = $connection;
			$connection->dispatchPackage($this->socketParser->packConnected($request));
			($this->callback)($connection);
		});
		$this->server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
			$conn = isset($this->connections[$frame->fd])?$this->connections[$frame->fd]:null;
			if ($conn and $conn instanceof WebSocketConnection) {
				$conn->queueFrame($frame);
			}
		});
	}

	/**
	 * @return WebSocketConnection
	 */
	public function createWsConnection(\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq, \Symfony\Component\HttpFoundation\Request $request) {
		$connection = new WebSocketConnection($this->server, $this, $sreq->fd, $request, $this->socketParser);
		$connection->instance(\Swooen\Communication\Writer::class, $this->createWsWriter($server, $sreq->fd));
		return $connection;
	}

	/**
	 * @return JsonWriter
	 */
	public function createWsWriter(\Swoole\WebSocket\Server $server, $fd) {
		return new JsonWriter($server, $fd);
	}

}
