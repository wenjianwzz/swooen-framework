<?php
namespace Swooen\Communication\Package\Features;

use Swooen\Util\Arr;

/**
 * 元数据
 * 
 * @author WZZ
 */
interface MetasFunction {

	public function meta(string $key, $default=null);

	public function allMetas();
	
}

/**
 * 元数据
 * 
 * @author WZZ
 */
trait MetasImpl {

    /**
     * @var array
     */
    protected $metas;

	public function meta(string $key, $default=null) {
		return Arr::get($this->metas, strtolower($key), $default);
	}

	public function allMetas() {
		return $this->metas;
	}
	
}
