<?php
namespace Swooen\IO;

interface Reader {
	
	/**
	 * 是否还有数据包可读
	 * @return boolean
	 */
	public function hasNext(): bool;

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
