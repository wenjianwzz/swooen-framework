<?php
namespace Swooen\Communication\Package\Features;

/**
 * 可感知IP
 * 
 * @author WZZ
 */
trait Routeable {

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
