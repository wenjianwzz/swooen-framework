<?php
namespace Swooen\Server\Swoole\Redis;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Writer;
use Swooen\Exception\Handler;
use \Swoole\Redis\Server;

class RedisExceptionHandler extends Handler {

	protected $dontReport = [
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
			if ($writer instanceof RedisWriter) {
				$writer->writeType(Server::ERROR, $e->getMessage());
			} else {
				$writer->write(Server::format(Server::ERROR, $e->getMessage()));
			}
		} catch (\Throwable $t2) {
			// 无法继续处理
		}
	}

}
