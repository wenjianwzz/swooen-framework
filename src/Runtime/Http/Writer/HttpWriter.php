<?php
namespace Swooen\Runtime\Http\Writer;

use Swooen\Communication\StdoutWriter;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class HttpWriter extends StdoutWriter {

	protected $sent = false;

	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function canWrite() {
		return !$this->sent;
	}
	
	/**
	 * 结束写入
	 */
	public function end(string $content=null) {
        if ($content) {
			$this->sent = true;
            $this->write($content);
        }
	}

	public function writeMeta(string $name, string $value) {
		return $this->header($name, $value);
    }

	public function header(string $name, string $value) {
        header("{$name}: {$value}");
		return true;
    }
	

}
