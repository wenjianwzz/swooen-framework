<?php
namespace Swooen\Server\Swoole\WebSocket;

use Swooen\Server\Swoole\SwooleConnection;
use Swooen\Server\Swoole\WebSocket\Package\WebSocketParser;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author WZZ
 */
class WebSocketConnection extends SwooleConnection {

	protected $buffer = '';

	/**
	 * @var Request
	 */
	protected $request;

	protected $webSocketParser;

	public function __construct(\Swoole\Server $server, WebSocketConnectionFactory $factory, $fd, Request $request, WebSocketParser $webSocketParser) {
        parent::__construct($server, $factory, $fd);
		$this->webSocketParser = $webSocketParser;
		$this->request = $request;
	}

    public function queueFrame(\Swoole\WebSocket\Frame $frame) {
		if ($frame instanceof \Swoole\WebSocket\CloseFrame) {
			$this->dispatchPackage($this->webSocketParser->packClose($this->request));
		} else {
			$this->buffer .= $frame->data;
			if ($frame->finish) {
				$packages = $this->webSocketParser->packData($this->request, $this->buffer);
				$this->buffer = '';
				if (!is_array($packages)) {
					$packages = [$packages];
				}
				foreach($packages as $package) {
					$this->dispatchPackage($package);
				}
			}
		}
    }

	public function onClientClosed() {
		$this->closed = true;
		$this->dispatchPackage($this->webSocketParser->packClose($this->request));
		$this->packageChannel->push(null);
	}
}
