<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

/**
 * @author WZZ
 */
class WebSocketClosePackage extends WebSocketPackage {

    public function __construct(\Symfony\Component\HttpFoundation\Request $request) {
        parent::__construct($request);
		$this->routePath = 'CLOSE '.str_replace('//', '/', $this->request->getPathInfo());
	}
}
