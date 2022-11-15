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

    protected $logger;

    public function __construct(?LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handle(HandleContext $context, Package $package, Writer $nestedWriter, callable $next) {
        $this->logPackage('income package', $package);
        $writer = new PackageLoggerWriter($nestedWriter, $this);
        $context->instance(Writer::class, $writer);
        $next($context, $package, $writer);
        $context->instance(Writer::class, $nestedWriter);
    }

    public function logPackage($msg, Package $package) {
        if ($this->logger) {
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
            $this->logger->debug($msg, $context);
        }
    }

}

/**
 * 包处理器
 * @author WZZ
 */
class PackageLoggerWriter implements Writer {

    /**
     * @var PackageLogger
     */
    protected $logger;

    /**
     * @var Writer
     */
    protected $nestedWriter;

    public function __construct(Writer $nestedWriter, PackageLogger $logger) {
        $this->logger = $logger;
        $this->nestedWriter = $nestedWriter;
    }

	
	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function send(Package $package): bool {
        $this->logger->logPackage('send package', $package);
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
        $this->logger->logPackage('end with package', $package);
        return $this->nestedWriter->end($package);
    }

}
