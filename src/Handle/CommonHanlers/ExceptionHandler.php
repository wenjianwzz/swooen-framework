<?php
namespace Swooen\Handle\CommonHanlers;

use Psr\Log\LoggerInterface;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;
use Swooen\Server\Generic\Package\HttpResponsePackage;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * 包处理器
 * @author WZZ
 */
class ExceptionHandler extends PackageHandler {

    protected $logger;

	protected $dontReport = [
		HttpException::class
	];

    public function __construct(?LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handle(HandleContext $context, Package $package, Writer $writer, ?callable $next) {
        try {
            $next($context, $package, $writer);
        } catch (\Throwable $t) {
            $this->report($t);
            $this->render($t, $writer);
        }
    }

    protected function shouldReport(\Throwable $e) {
        foreach ($this->dontReport as $type) {
            if (is_a($e, $type)) {
                return false;
            }
        }
        return true;
    }

	protected function report(\Throwable $e) {
        if (empty($this->logger)) {
            return;
        }
		try {
			if ($this->shouldReport($e)) {
                $this->logger->error($e);
            }
		} catch (\Throwable $t2) {
			// 无法继续处理
		}
	}

	protected function render(\Throwable $e, Writer $writer) {
        $response = new HttpResponsePackage($e->getMessage());
        if ($e instanceof HttpException) {
            $response->setHttpStatusCode($e->getCode());
        }
        $writer->end($response);
	}

}
