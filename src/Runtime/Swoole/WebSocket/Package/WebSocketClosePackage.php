<?php
namespace Swooen\Runtime\Swoole\WebSocket\Package;


/**
 * @author WZZ
 */
class WebSocketClosePackage extends WebSocketPackage {
    
	public function getRoutePath() {
		return 'CLOSE '.$this->path;
	}
}
