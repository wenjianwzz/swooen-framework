<?php
namespace Swooen\Runtime\Swoole\WebSocket;

use Swooen\Runtime\Swoole\Http\HttpConnectionFactory;
use Swooen\Runtime\Swoole\WebSocket\Package\WebSocketParser;
use Swooen\Runtime\Swoole\WebSocket\Writer\JsonWriter;

/**
 * 
 * @author WZZ
 */
class WebSocketConnectionFactory extends HttpConnectionFactory {
	
	/**
	 * @var WebSocketParser
	 */
	protected $socketParser;

	public function __construct(WebSocketParser $socketParser=null) {
		empty($socketParser) and $socketParser = $this->createSocketParser();
		$this->setParser($this->createParser());
		$this->setSocketParser($socketParser);
	}
	
	public function onConnected(\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq) {
		$request = $this->packRequest($sreq);
		$connection = $this->createWsConnection($server, $sreq, $request);
		$this->connections[$sreq->fd] = $connection;
		$connection->dispatchPackage($this->socketParser->packConnected($request));
		($this->callback)($connection);
	}
	
	public function onFrame(\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
		$conn = isset($this->connections[$frame->fd])?$this->connections[$frame->fd]:null;
		if ($conn and $conn instanceof WebSocketConnection) {
			$conn->queueFrame($frame);
		}
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

	/**
	 * @return WebSocketConnection
	 */
	public function createWsConnection(\Swoole\WebSocket\Server $server, \Swoole\Http\Request $sreq, \Symfony\Component\HttpFoundation\Request $request) {
		$connection = new WebSocketConnection($server, $this, $sreq->fd, $request, $this->socketParser);
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
