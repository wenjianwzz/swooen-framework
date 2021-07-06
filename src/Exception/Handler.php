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
		foreach ($this->dontReport as $type) {
			if (is_a($e, $type)) {
				return;
			}
		}
		if ($logger) {
			$logger->error($e);
		}
	}

	public function render(\Throwable $e, Writer $writer) {
		if ($e instanceof HttpException) {
			// $writer->status($e->getStatusCode());
			// $code = $e->getStatusCode();
			// $writer->write($code. ' ');
			// $writer->write(isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : $e->getMessage());
		} else {
		}
		$writer->write($e->getFile().':'.$e->getLine()."\n".$e->getMessage()."\n".$e->getTraceAsString());
	}

}
