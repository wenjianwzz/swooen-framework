<?php
namespace Swooen\Communication;

class StdoutWriter implements Writer {
	
	public function canWrite() {
        return true;
    }

	public function write(string $content) {
        echo $content;
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
        echo $name, $value, PHP_EOL;
		return true;
    }
	
	public function writeCookie(string $name, string $value, string $expire) {
        echo $name, $value, $expire, PHP_EOL;
		return true;
    }

}
