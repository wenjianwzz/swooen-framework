<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Server\Swoole\SwooleConnection;
use Swoole\Coroutine\Channel;
use Swoole\WebSocket\CloseFrame;

/**
 * @author WZZ
 */
class WebSocketConnection extends SwooleConnection {

	/**
	 * @var Channel
	 */
	protected $frameChannel;

	public function __construct(\Swoole\Server $server, WsJsonConnectionFactory $factory, $fd) {
        parent::__construct($server, $factory, $fd);
		$this->frameChannel = new Channel(64);
	}

    public function queueFrame(\Swoole\WebSocket\Frame $frame) {
		$this->frameChannel->push($frame, -1);
    }

	public function onClientClosed() {
		parent::onClientClosed();
		$this->queueFrame(new CloseFrame());
	}

	/**
	 * @return \Swoole\WebSocket\Frame
	 */
    public function popFrame() {
		return $this->frameChannel->pop(-1);
    }

	public function hasFrames() {
		return !$this->frameChannel->isEmpty() || !$this->closed;
	}

	public function __destruct() {
		echo __METHOD__ . PHP_EOL;
	}
}
