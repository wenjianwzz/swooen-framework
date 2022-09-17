<?php
namespace Swooen\Communication\Package;

/**
 * 封装来往通信报文
 * 信息分为三类，输入、元数据（如HTTP头部信息）、追踪数据（如Http中的Cookie）
 * @author WZZ
 */
interface Package {

	/**
	 * 获取输入
	 * @return mixed
	 */
	public function input(string $key, $default=null);

	/**
	 * 所有输入作为一个数组返回, 仅当输入可以转换成数组的时候
	 * @return mixed
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
	 * 是否是数组类型
	 * @return boolean
	 */
	public function isArray();

	/**
	 * 是否是字符串类型
	 * @return boolean
	 */
	public function isString();

	/**
	 * 获取字符串数据
	 * @return string
	 */
	public function getString();
	
}
