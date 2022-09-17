<?php
namespace Swooen\Communication;

/**
 * 可感知IP的数据包
 * 
 * @author WZZ
 */
interface IPAwarePackage extends Package {

	/**
	 * 获取路由Path，用来供路由判断
	 * @return string
	 */
	public function getIP();
	
}
