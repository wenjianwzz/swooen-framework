<?php
namespace Swooen\IO\Package\Features;
/**
 * 可感知远程地址
 * 
 * @author WZZ
 */
interface RemoteAddressAware {

    /**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getRemoteAddress(): string;
	
}
/**
 * 可感知远程地址
 * 
 * @author WZZ
 */
trait RemoteAddressAwareFeature {

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
