<?php
namespace Swooen\Communication;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
interface Package {

	const TYPE_RAW = 0x0001;

	const TYPE_ARRAY = 0x0002;

	/**
	 * 获取输入
	 * @return mixed
	 */
	public function input(string $key, $default=null);

	/**
	 * 所有输入作为一个数组返回
	 * @return array
	 */
	public function inputs();

	/**
	 * 获取元数据
	 * @return mixed
	 */
	public function meta(string $key, $default=null);

	/**
	 * 所有元数据
	 * @return array
	 */
	public function metas();

	/**
	 * 获取数据包类型
	 * @return int
	 */
	public function getType();

	/**
	 * 获取原始内容类型
	 * @return string
	 */
	public function raw();
	
}
