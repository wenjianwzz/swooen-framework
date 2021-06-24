<?php
namespace Swooen\Http;

use Swooen\Http\Writer\WebSocketConnection;

class WebSocketController {

	protected $loop = true;

	protected $willClose = false;

	protected $buffer = '';

	protected $eventChannel;
    
    public function __invoke(\Swooen\Core\Container $container, \Swooen\Http\Request $req, WebSocketConnection $connection) {
		if ($this->connect($container, $req, $connection)) {
			$connection->accept();
		} else {
			$connection->end();
			return;
		}
		$this->eventChannel = new \Swoole\Coroutine\Channel(16); 
		if (false === $this->connected($container, $req, $connection)) {
			$connection->end();
			return;
		}
        while ($this->loop) {
			$frame = $connection->read();
            if (false === $frame) {
				$errno = swoole_last_error();
				echo '['.$connection->getFd() . '] error : ' . $errno . PHP_EOL;
                break;
            } else if ($this->willClose) {
				// 读取没有错误，可以关闭
				$connection->end();
				break;
			} else if ($frame instanceof \Swoole\WebSocket\CloseFrame || '' === $frame ) {
				$this->eventChannel->push(['type'=>'close']);
                break;
            } else {
				$this->buffer .= $frame->data;
				if ($frame->finish) {
					$this->eventChannel->push(['type'=>'frame', 'data'=>$this->buffer]);
					$this->buffer = '';
				}
			}
		}
	}
	
    /**
	 * 控制连接，如果返回false将会终止连接
     */
	public function connect(\Swooen\Core\Container $container, \Swooen\Http\Request $req, WebSocketConnection $connection) {
		return true;
	}

	/**
	 * 连接之后执行
	 */
	public function connected(\Swooen\Core\Container $container, \Swooen\Http\Request $req, WebSocketConnection $connection) {
	}

	public function data($data, \Swooen\Core\Container $container, \Swooen\Http\Request $req, WebSocketConnection $writer) {
	}

	public function close(\Swooen\Core\Container $container, \Swooen\Http\Request $req) {
	}
}
