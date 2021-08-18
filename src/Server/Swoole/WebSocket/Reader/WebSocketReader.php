<?php
namespace Swooen\Server\Swoole\WebSocket\Reader;

use Swooen\Communication\RawPackage;
use Swooen\Communication\Reader;
use Swooen\Communication\TerminatePackage;
use Swooen\Server\Swoole\WebSocket\WebSocketConnection;
use Swooen\Server\Swoole\WebSocket\WsJsonConnectionFactory;
use Swoole\Coroutine\Channel;

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
		return new RawPackage($content, []);
	}

	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {
		do {
			$frame = $this->connection->popFrame();
			if ($frame instanceof \Swoole\WebSocket\CloseFrame) {
				return new TerminatePackage();
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
