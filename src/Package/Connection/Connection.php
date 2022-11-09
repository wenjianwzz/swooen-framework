<?php
namespace Swooen\Package\Connection;

use Swooen\Container\ContainerInterface;

/**
 * 代表和对端建立的连接，负责和对端进行通信
 * 
 * @author WZZ
 */
interface Connection extends ContainerInterface {
	
	/**
	 * @return \Swooen\Package\Writer
	 */
	public function getWriter();

	/**
	 * 终止连接
	 */
	public function terminate();

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isClosed();

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream();

	public function listenPackage(callable $callable);

}
