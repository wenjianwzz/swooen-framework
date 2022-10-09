<?php
namespace Swooen\Communication\Writer;

use Swooen\Communication\Package\Package;

interface Writer {
	
	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function send(Package $package);
	
	/**
	 * 是否可以给对方发送数据包
	 * @return boolean
	 */
	public function writable();

	/**
	 * 向对方发送内容，并终止连接
	 */
	public function end(Package $package=null);
	
}
