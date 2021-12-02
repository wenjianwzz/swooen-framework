<?php
namespace Swooen\Communication;

/**
 * 连接工厂
 * 
 * @author WZZ
 */
interface ConnectionFactory {
	
	/**
	 * 设置新链接回调
	 */
	public function onConnection(callable $callback);

}
