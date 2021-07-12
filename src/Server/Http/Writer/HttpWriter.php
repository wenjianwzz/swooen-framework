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
	
	public function writeMeta(string $name, string $value) {
		$this->headerSent = true;
        header("{$name}: {$value}");
		return true;
    }
	

}