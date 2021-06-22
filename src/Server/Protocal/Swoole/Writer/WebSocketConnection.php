<?php
namespace Swooen\Http\Writer;

/**
 * 控制Socket的响应
 */
class WebSocketConnection extends SwooleWriter {

    public function write($content) {
        $this->responser->push($content);
    }

    public function read() {
        return $this->responser->recv();
    }

    /**
     * 接受Socket连接
     */
    public function accept() {
        $this->responser->upgrade();
    }

    public function end() {
        $this->responser->close();
    }

}
