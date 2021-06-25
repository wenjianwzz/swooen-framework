<?php
namespace Swooen\Communication;

/**
 * @author WZZ
 */
interface Reader {
	
	/**
	 * 获取下一个数据包
	 * @return Package
	 */
	public function next();

	/**
	 * 缓冲区是否存在更多对方发送的数据包
	 * @return boolean
	 */
	public function hasNext();

}
