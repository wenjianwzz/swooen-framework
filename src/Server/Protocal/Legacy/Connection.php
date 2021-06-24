<?php
namespace Swooen\Server\Protocal\Legacy;

use Swooen\Communication\Connection as ConnectionInterface;
use Swooen\Communication\Package;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class Connection implements ConnectionInterface {
	
	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next() {}

	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function canPush() {}

	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function push(Package $package) {}

	/**
	 * 终止连接，并向对方发送终止原因
	 */
	public function end(string $reason) {}

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isEnd() {}

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream() {}

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext() {}

}
