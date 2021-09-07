<?php
namespace Swooen\Server\Swoole\WebSocket\Package;


/**
 * @author WZZ
 */
class WebSocketConnectedPackage extends WebSocketPackage {
    
	public function getRoutePath() {
		return 'CONNECT '.$this->path;
	}
}
