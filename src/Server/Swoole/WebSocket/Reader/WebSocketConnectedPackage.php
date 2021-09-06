<?php
namespace Swooen\Server\Swoole\WebSocket\Reader;


/**
 * @author WZZ
 */
class WebSocketConnectedPackage extends WebSocketPackage {
    
	public function getRoutePath() {
		return 'CONNECT '.$this->path;
	}
}
