<?php
namespace Swooen\Runtime\Swoole\Redis;

use Swooen\Communication\NilPackage;
use Swooen\Communication\Package\Package;
use Swooen\Communication\Writer;
use Swoole\Redis\Server;

class RedisWriter implements Writer {

    protected $server;

    protected $fd;

	protected $closed = false;

    public function __construct(Server $server, $fd) {
        $this->server = $server;
        $this->fd = $fd;
    }

	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function send(Package $package) {
        if ($metas = $package->metas()) {
            array_map(function($name, $value) {
                $this->writeMeta($name, $value);
            }, array_keys($metas), $metas);
        }
        if ($package instanceof NilPackage) {
            return $this->writeType(Server::NIL, '');
        }
        if ($package->isString()) {
            return $this->write($package->getString());
        } else {
            return $this->write($this->pack($package));
        }
	}

    /**
     * 将package的inputs转换成字符串
     */
    public function pack(Package $package) {
        return print_r($package->inputs(), true);
    }
	
	public function canWrite() {
        return $this->closed;
    }

    public function writeType($type, string $message) {
        $this->server->send($this->fd, Server::format($type, $message));
    }

	public function write(string $content) {
        $this->writeType(Server::STRING, $content);
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
		return true;
    }

	/**
	 * Get the value of closed
	 */
	public function isClosed() {
		return $this->closed;
	}

	/**
	 * Set the value of closed
	 */
	public function setClosed($closed): self {
		$this->closed = $closed;
		return $this;
	}

	public function end(string $content) {
        if ($content) {
            $this->write($content);
        }
    }
}
