<?php
namespace Swooen\Server;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
interface Listener {
	
	/**
	 * 开始监听, 获得新的连接
	 * @return Connection
	 */
	public function listen();

}
