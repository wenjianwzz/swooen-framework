<?php
namespace Swooen\Communication;

/**
 * 连接工厂
 * 
 * @author WZZ
 */
interface ConnectionFactory {
	
	/**
	 * 开始监听, 获得新的连接
	 * @return Connection
	 */
	public function make();

}
