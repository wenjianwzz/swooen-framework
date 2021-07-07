<?php
namespace Swooen\Server\Http\Writer;

use Swooen\Communication\StdoutWriter;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class HttpWriter extends StdoutWriter {

	protected $headerSent = false;

	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function canWrite() {
		return !$this->headerSent;
	}

	public function write(string $content) {
		echo $content;
		return true;
	}
	
	public function writeMeta(string $name, string $value) {
		$this->headerSent = true;
        header("{$name}: {$value}");
		return true;
    }
	
	public function writeCookie(string $name, string $value, string $expire) {
		$this->headerSent = true;
        setcookie($name, $value, $expire, '', '', false, false);
		return true;
    }

}
