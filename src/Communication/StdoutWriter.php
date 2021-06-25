<?php
namespace Swooen\Communication;

class StdoutWriter implements Writer {
	
	public function canWrite() {
        return true;
    }

	public function write(string $content) {
        echo $content;
    }

}
