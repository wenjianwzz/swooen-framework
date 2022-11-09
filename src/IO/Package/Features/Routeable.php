<?php
namespace Swooen\IO\Package\Features;

/**
 * 可供路由的
 * 
 * @author WZZ
 */
interface Routeable {

    /**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getRoutePath();
	
}

/**
 * 可供路由的
 * 
 * @author WZZ
 */
trait RouteableFeature {

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
