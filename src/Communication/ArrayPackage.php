<?php
namespace Swooen\Communication;

use Illuminate\Support\Arr;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class ArrayPackage implements Package {

	protected $inputs;

	protected $metas;

	public function __construct(array $inputs, array $metas) {
		$this->inputs = $inputs;
		$this->metas = $metas;
	}

	public function input(string $key, $default=null) {
		return Arr::get($this->inputs, $key, $default);
	}

	public function meta(string $key, $default=null) {
		return Arr::get($this->metas, $key, $default);
	}

	public function inputs() {
		return $this->inputs;
	}

	public function metas() {
		return $this->metas;
	}

	/**
	 * 获取数据包类型
	 * @return int
	 */
	public function getType() {
		return self::TYPE_ARRAY;
	}

	/**
	 * 获取原始输入内容
	 * @return string
	 */
	public function raw() {
		return false;
	}
	
}
