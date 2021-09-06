<?php
namespace Swooen\Server\Swoole\WebSocket\Reader;

use Swooen\Communication\Reader;
use Swooen\Server\Swoole\WebSocket\WebSocketConnection;

/**
 * @author WZZ
 */
class WebSocketReader implements Reader {

    /**
     * @var \Swoole\WebSocket\Server
     */
    protected $server;

	/**
	 * @var WebSocketConnection
	 */
    protected $connection;

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	protected $buffer = '';

    public function __construct(\Swoole\WebSocket\Server $server, WebSocketConnection $connection, \Symfony\Component\HttpFoundation\Request $request) {
        $this->server = $server;
        $this->connection = $connection;
		$this->request = $request;
    }

	public function getRequest() : \Symfony\Component\HttpFoundation\Request {
		return $this->request;
	}

	public function hasNext() {
		return $this->connection->hasFrames();
	}

	protected function packData($content) {
		$path = str_replace('//', '/', $this->request->getPathInfo());
		$inputs = $this->request->request?$this->request->request->all():[];
		$metas = array_map('reset', $this->request->headers->all());
		return new WebSocketPackage($path, $inputs, $metas, $content, $this->request->getClientIp());
	}

	protected function packConnected() {
		$path = str_replace('//', '/', $this->request->getPathInfo());
		$inputs = $this->request->request?$this->request->request->all():[];
		$metas = array_map('reset', $this->request->headers->all());
		return new WebSocketConnectedPackage($path, $inputs, $metas, '', $this->request->getClientIp());
	}
	
	protected function packClose() {
		$path = str_replace('//', '/', $this->request->getPathInfo());
		$inputs = $this->request->request?$this->request->request->all():[];
		$metas = array_map('reset', $this->request->headers->all());
		return new WebSocketClosePackage($path, $inputs, $metas, '', $this->request->getClientIp());
	}

	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {
		do {
			$frame = $this->connection->popFrame();
			if ($frame instanceof \Swoole\WebSocket\CloseFrame) {
				return $this->packClose();
            } else if ($frame instanceof ConnectedVirtualFrame) {
				// 连接
				return $this->packConnected();
            } else {
				$this->buffer .= $frame->data;
				if ($frame->finish) {
					$package = $this->packData($this->buffer);
					$this->buffer = '';
					return $package;
				}
			}
		 } while (true);
	}
}
