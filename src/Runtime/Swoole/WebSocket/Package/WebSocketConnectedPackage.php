<?php
namespace Swooen\Runtime\Swoole\WebSocket\Package;


/**
 * @author WZZ
 */
class WebSocketConnectedPackage extends WebSocketPackage {
    
	public function getRoutePath() {
		return 'CONNECT '.$this->path;
	}
}
