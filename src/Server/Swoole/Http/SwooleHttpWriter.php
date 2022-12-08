<?php
namespace Swooen\Server\Swoole\Http;

use Swooen\Package\Package;
use Swooen\Server\Generic\Package\HttpWriter;

/**
 * @author WZZ
 */
class SwooleHttpWriter extends HttpWriter {

	/**
	 * @var \Swoole\Http\Response
	 */
	protected $response;

	public function __construct(\Swoole\Http\Response $response) {
		$this->response = $response;
	}

	public function end(?Package $package) {
        if ($package) {
            $this->send($package);
        }
		$this->response->end();
	}

	public function writable(): bool {
        return $this->response->isWritable();
    }

	public function write(string $content): bool {
		$this->response->write($content);
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
		$this->response->header($name, $value);
		return true;
    }
}
