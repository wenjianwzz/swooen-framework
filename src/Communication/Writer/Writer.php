<?php
namespace Swooen\Communication;

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
	public function canWrite();

	/**
	 * 向对方发送内容，并终止连接
	 */
	public function end(string $content);

	/**
	 * 给对方发送数据
	 * @return boolean
	 */
	public function write(string $content);
	
	/**
	 * 写入元数据
	 * @return boolean
	 */
	public function writeMeta(string $name, string $value);
	
}
