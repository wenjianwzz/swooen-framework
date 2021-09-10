<?php
namespace Swooen\Server\Swoole\Exception;

use Swooen\Communication\Writer;
use Swooen\Exception\Handler as ExceptionHandler;

class StreamHandler extends ExceptionHandler {

		public function render(\Throwable $e, Writer $writer) {
		try {
			$writer->write($e->getFile().':'.$e->getLine()."\n".$e->getMessage()."\n".$e->getTraceAsString());
		} catch (\Throwable $t2) {
			// 无法继续处理
		}
	}

}
