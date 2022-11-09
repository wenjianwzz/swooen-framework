<?php
namespace Swooen\Runtime\Swoole\Http\Writer;

use Swooen\IO\Package\Package;
use Swooen\Runtime\Http\Writer\JsonWriter as LegacyJsonWriter;

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
    
    public function header(string $name, string $value) {
        $this->response->header($name, $value);
		return true;
    }
	
}
