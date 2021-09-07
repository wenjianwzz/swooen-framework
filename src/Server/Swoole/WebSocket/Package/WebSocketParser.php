<?php
namespace Swooen\Server\Swoole\WebSocket\Package;

use Swooen\Server\Swoole\WebSocket\WebSocketConnection;

/**
 * @author WZZ
 */
class WebSocketParser {

	public function packData(\Symfony\Component\HttpFoundation\Request $request, $content) {
		$path = str_replace('//', '/', $request->getPathInfo());
		$inputs = $request->query?$request->query->all():[];
		$metas = array_map('reset', $request->headers->all());
		return new WebSocketPackage($path, $inputs, $metas, $content, $request->getClientIp());
	}

	public function packConnected(\Symfony\Component\HttpFoundation\Request $request) {
		$path = str_replace('//', '/', $request->getPathInfo());
		$inputs = $request->query?$request->query->all():[];
		$metas = array_map('reset', $request->headers->all());
		return new WebSocketConnectedPackage($path, $inputs, $metas, '', $request->getClientIp());
	}
	
	public function packClose(\Symfony\Component\HttpFoundation\Request $request) {
		$path = str_replace('//', '/', $request->getPathInfo());
		$inputs = $request->query?$request->query->all():[];
		$metas = array_map('reset', $request->headers->all());
		return new WebSocketClosePackage($path, $inputs, $metas, '', $request->getClientIp());
	}

}
