<?php
namespace Swooen\Http\Writer;

/**
 * 控制Socket的响应
 */
class SwooleWriter extends Writer {
    
    protected $responser;

    public function __construct(\Swoole\Http\Response $responser) {
        $this->responser = $responser;    
    }

    public function write($content) {
        $this->responser->write($content);
    }

    public function getFd() {
        return $this->responser->fd;
    }

    public function header($name, $value) {
        $this->responser->header($name, $value);
    }

    public function cookie(string $name, string $value = "", int $expire = 0, string $path = "" , string $domain = "" , bool $secure = false, $httponly = false, string $samesite = '') {
        $this->responser->cookie(...func_get_args());
    }

    public function status($code) {
        $this->responser->status($code);
    }

    public function end() {
        $this->responser->end();
    }

    public function sendfile($path) {
        $this->responser->sendfile($path);
    }

}
