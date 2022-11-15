<?php
namespace Swooen\Handle\CommonHanlers;

use Psr\Log\LoggerInterface;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\HttpStatusAware;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\RawData;
use Swooen\Package\Features\RemoteAddressAware;
use Swooen\Package\Features\Routeable;

/**
 * 包处理器
 * @author WZZ
 */
class PackageLogger extends PackageHandler {

    public function handle(HandleContext $context, Package $package, Writer $nestedWriter, callable $next) {
        if ($context->has(LoggerInterface::class)) {
            $logger = $context->make(LoggerInterface::class);
            $this->logPackage('income package', $package, $logger);
            $writer = new PackageLoggerWriter($nestedWriter, function($msg, Package $package) use ($logger) {
                $this->logPackage($msg, $package, $logger);
            });
            $context->instance(Writer::class, $writer);
            $next($context, $package, $writer);
            $context->instance(Writer::class, $nestedWriter);
        } else {
            $next($context, $package, $nestedWriter);
        }
    }

    public function logPackage($msg, Package $package, LoggerInterface $logger) {
        $context = ['_class_' => get_class($package)];
        if ($package instanceof DataArray) {
            $context['dataArray'] = $package->allData();
        }
        if ($package instanceof Metas) {
            $context['metas'] = $package->allMetas();
        }
        if ($package instanceof Routeable) {
            $context['metas'] = $package->getRoutePath();
        }
        if ($package instanceof HttpStatusAware) {
            $context['httpStatusCode'] = $package->getHttpStatusCode();
        }
        if ($package instanceof RemoteAddressAware) {
            $context['remoteAddress'] = $package->getRemoteAddress();
        }
        if ($package instanceof RawData) {
            $context['rawData'] = $package->getRawData();
        }
        $logger->debug($msg, $context);
    }

}

/**
 * 包处理器
 * @author WZZ
 */
class PackageLoggerWriter implements Writer {

    /**
     * @var callable
     */
    protected $logger;

    /**
     * @var Writer
     */
    protected $nestedWriter;

    public function __construct(Writer $nestedWriter, callable $logger) {
        $this->logger = $logger;
        $this->nestedWriter = $nestedWriter;
    }

	
	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function send(Package $package): bool {
        call_user_func($this->logger, 'send package', $package);
        return $this->nestedWriter->send($package);
    }
	
	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function writable(): bool {
        return $this->nestedWriter->writable();
    }

	/**
	 * 向对方发送内容，并终止连接
	 */
	public function end(?Package $package) {
        call_user_func($this->logger, 'send package', $package);
        return $this->nestedWriter->end($package);
    }

}
