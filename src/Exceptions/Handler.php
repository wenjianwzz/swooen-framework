<?php
namespace Swooen\Exceptions;

use Psr\Log\LoggerInterface;
use Swooen\Core\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler {

	protected $dontReport = [
		HttpException::class
	];

	public function report(\Throwable $e, Container $container) {
		foreach ($this->dontReport as $type) {
			if (is_a($e, $type)) {
				return;
			}
		}
		if ($container->bound(\Psr\Log\LoggerInterface::class)) {
			$container->call(function(LoggerInterface $logger, \Throwable $e) {
				$logger->error($e);
			}, ['e' => $e]);
		}
	}

	public function render(\Throwable $e, \Swooen\Http\Writer\Writer $writer, Container $container) {
		if ($e instanceof HttpException) {
			$writer->status($e->getStatusCode());
			$code = $e->getStatusCode();
			$writer->write($code. ' ');
			$writer->write(isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : $e->getMessage());
			$writer->end();
		} else {
			$writer->response(new Response($e->getMessage()."\n".$e->getTraceAsString(), 500));
		}
		
	}

}
