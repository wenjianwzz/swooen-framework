<?php
namespace Swooen\Server\Swoole\WebSocket\Reader;


/**
 * @author WZZ
 */
class WebSocketClosePackage extends WebSocketPackage {
    
	public function getRoutePath() {
		return 'CLOSE '.$this->path;
	}
}
