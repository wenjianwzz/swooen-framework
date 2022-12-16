<?php
namespace Swooen\Server\Swoole\Http\WebSocket;

use Swooen\Handle\Writer\StdoutWriter;
use Swooen\Package\Package;

/**
 * @author WZZ
 */
class WebSocketWriter extends StdoutWriter {

    /**
     * @var WebSocketConnection
     */
    protected $connection;

    public function __construct(WebSocketConnection $connection) {
        $this->connection = $connection;
    }

	public function end(?Package $package) {
        if ($package) {
            $this->send($package);
        }
	}

	public function terminate() {
        $this->connection->terminate();
	}

	public function writable(): bool {
        return $this->connection->writable();
    }

	public function write(string $content): bool {
		$this->connection->write($content);
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
		return true;
    }
	
}