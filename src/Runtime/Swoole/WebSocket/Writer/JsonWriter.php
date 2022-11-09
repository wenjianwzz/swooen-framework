<?php
namespace Swooen\Runtime\Swoole\WebSocket\Writer;

use Swooen\Package\Package\Package;
use Swooen\Runtime\Http\Writer\JsonWriter as LegacyJsonWriter;

/**
 * @author WZZ
 */
class JsonWriter extends LegacyJsonWriter {

    /**
     * @var \Swoole\WebSocket\Server
     */
    protected $server;

    protected $fd;

    public function __construct(\Swoole\WebSocket\Server $server, $fd) {
        $this->server = $server;
        $this->fd = $fd;
    }

    public function write(string $content) {
        $this->server->push($this->fd, $content, WEBSOCKET_OPCODE_TEXT);
    }

    public function end(string $content = null) {
        if ($content) {
            $this->write($content);
        }
        $this->server->disconnect($this->fd);
    }
    
	public function canWrite() {
		return $this->server->isEstablished($this->fd);
	}
	
	public function send(Package $package) {
        if ($package->isString()) {
            return $this->write($package->getString());
        } else {
            return $this->write(json_encode($package->inputs()));
        }
	}
	
}
