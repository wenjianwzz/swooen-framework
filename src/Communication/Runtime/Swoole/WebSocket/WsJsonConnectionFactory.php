<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Exception\Handler;
use Swooen\Server\Swoole\Http\HttpConnectionFactory;
use Swooen\Server\Swoole\WebSocket\Package\WebSocketParser;
use Swooen\Server\Swoole\WebSocket\Writer\JsonWriter;
use \Swoole\WebSocket\Server;

/**
 * 
 * @author WZZ
 */
class WsJsonConnectionFactory extends WebSocketConnectionFactory {
		
	/**
	 * @return WebSocketParser
	 */
	public function createSocketParser() {
		return new WebSocketParser();
	}

	/**
	 * @return JsonWriter
	 */
	public function createWsWriter(\Swoole\WebSocket\Server $server, $fd) {
		return new JsonWriter($server, $fd);
	}

}
