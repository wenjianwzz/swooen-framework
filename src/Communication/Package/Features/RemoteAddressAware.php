<?php
namespace Swooen\Communication\Package\Features;

/**
 * 可感知IP
 * 
 * @author WZZ
 */
trait RemoteAddressAware {

    /**
     * @var string
     */
    protected $remoteAddress;

    /**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getRemoteAddress(): string {
        return $this->remoteAddress;
    }
	
}
