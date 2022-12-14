<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Swooen\Handle\PackageDispatcher;
use Swooen\Server\Swoole\SwooleConnection;
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

	public function __construct(\Swoole\Server $server, $fd, Request $request, PackageDispatcher $packageDispatcher) {
        parent::__construct($server, $fd, $packageDispatcher);
		$this->request = $request;
	}

    public function pushFrame(\Swoole\WebSocket\Frame $frame) {
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
