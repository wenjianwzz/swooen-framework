<?php
namespace Swooen\Runtime\Swoole\WebSocket;

use Swooen\Exception\Handler;
use Swooen\Runtime\Swoole\Http\HttpConnectionFactory;
use Swooen\Runtime\Swoole\WebSocket\Package\WebSocketParser;
use Swooen\Runtime\Swoole\WebSocket\Writer\JsonWriter;
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
