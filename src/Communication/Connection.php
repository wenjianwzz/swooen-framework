<?php
namespace Swooen\Communication;

/**
 * 代表和对端建立的连接，负责和对端进行通信
 * 
 * @author WZZ
 */
interface Connection extends \Psr\Container\ContainerInterface {
	
	/**
	 * @return \Swooen\Communication\Writer
	 */
	public function getWriter();

	/**
	 * @return \Swooen\Communication\Reader
	 */
	public function getReader();

	/**
	 * 终止连接，并向对方发送终止原因
	 */
	public function end(string $reason);

	/**
	 * 当前连接是否终止
	 * @return boolean
	 */
	public function isEnd();

	/**
	 * 是否是数据流
	 * @return boolean
	 */
	public function isStream();

}
