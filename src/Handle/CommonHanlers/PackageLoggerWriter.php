<?php
namespace Swooen\Handle\CommonHanlers;

use Psr\Log\LoggerInterface;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;

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
