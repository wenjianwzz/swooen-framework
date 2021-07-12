<?php
namespace Swooen\Communication;

/**
 * 可被路由的数据包
 * 
 * @author WZZ
 */
interface RouteablePackage extends Package {

	/**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getRoutePath();
	
}
