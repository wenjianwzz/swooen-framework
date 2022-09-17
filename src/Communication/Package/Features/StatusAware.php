<?php
namespace Swooen\Communication\Package\Features;

/**
 * 给对端发送状态
 * 
 * @author WZZ
 */
interface StatusNotice {

    /**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getStatus();
	
}

trait StatusAwareImpl {

    /**
     * @var string
     */
    protected $routePath;

    /**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getRoutePath(): string {
        return $this->routePath;
    }
	
}
