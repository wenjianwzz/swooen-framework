<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Swooen\Handle\ConnectionContext;
use Swooen\Handle\PackageDispatcher;
use Swooen\Package\Package;
use Swooen\Server\Swoole\SwooleConnection;
use Swoole\WebSocket\Server;
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

	/**
	 * @var PackageDispatcher
	 */
	protected $dispatcher;

	/**
	 * @var WebSocketBooter
	 */
	protected $booter;

	/**
	 * @var ConnectionContext
	 */
	protected $connectionContext;

	public function __construct(WebSocketBooter $booter, ConnectionContext $connectionContext, \Swoole\WebSocket\Server $server, $fd, Request $request) {
        parent::__construct($server, $fd);
		$this->request = $request;
		$this->booter = $booter;
		$this->connectionContext = $connectionContext;
		$this->queuePackage(new WebSocketOpenPackage($this->request));
	}

    public function pushFrame(\Swoole\WebSocket\Frame $frame) {
		if ($frame instanceof \Swoole\WebSocket\CloseFrame) {
			$this->queuePackage(new WebSocketClosePackage($this->request));
		} else {
			$this->buffer .= $frame->data;
			if ($frame->finish) {
				$data = $this->buffer;
				$package = new WebSocketPackage($this->request);
				$package->setRawData($data);
				$this->queuePackage($package);
			}
		}
    }

	public function handlePackage(Package $package) {
		$this->booter->handle($package, $this);
	}

	public function write(string $content) {
		if (!$this->writable()) {
			return false;
		}
		assert($this->server instanceof Server);
		$this->server->push($this->fd, $content, WEBSOCKET_OPCODE_TEXT);
	}
    
	public function writable(): bool {
		assert($this->server instanceof Server);
		return $this->server->isEstablished($this->fd);
	}

	public function onClientClosed() {
		$this->closed = true;
		$this->queuePackage(new WebSocketClosePackage($this->request));
		$this->packageChannel->push(null);
	}
}
