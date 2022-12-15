<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Swooen\Server\Generic\Package\HttpRequestPackage;

/**
 * @author WZZ
 */
class WebSocketPackage extends HttpRequestPackage {

    public function __construct(\Symfony\Component\HttpFoundation\Request $request) {
        parent::__construct($request);
		$this->routePath = 'DATA '.str_replace('//', '/', $this->request->getPathInfo());
	}
}
