<?php
namespace Swooen\Exception;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Writer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler {

	protected $dontReport = [
		HttpException::class
	];

	public function report(\Throwable $e, LoggerInterface $logger = null) {
		try {
			if (empty($logger)) {
				return;
			}
			foreach ($this->dontReport as $type) {
				if (is_a($e, $type)) {
					return;
				}
			}
			$logger->error($e);
		} catch (\Throwable $t2) {
			// 无法继续处理
		}
	}

	public function render(\Throwable $e, Writer $writer) {
		try {
			$writer->end($e->getFile().':'.$e->getLine()."\n".$e->getMessage()."\n".$e->getTraceAsString());
		} catch (\Throwable $t2) {
			// 无法继续处理
		}
	}

}
