<?php
namespace Swooen\Server\Swoole\Http\Writer;

use Swooen\Communication\Package;
use Swooen\Server\Http\Writer\JsonWriter as LegacyJsonWriter;

/**
 * @author WZZ
 */
class JsonWriter extends LegacyJsonWriter {

    /**
     * @var \Swoole\Http\Response
     */
    protected $response;

    public function __construct(\Swoole\Http\Response $response) {
        $this->response = $response;
    }

    public function write(string $content) {
        $this->response->write($content);
    }

    public function end(string $content = null) {
        $this->response->end($content);
    }
    
    public function writeMeta(string $name, string $value) {
		$this->headerSent = true;
        $this->response->header($name, $value);
		return true;
    }
	
}
