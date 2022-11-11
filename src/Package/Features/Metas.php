<?php
namespace Swooen\Package\Features;

use Wenjianwzz\Tool\Util\Arr;

/**
 * 元数据
 * 
 * @author WZZ
 */
interface Metas {

	public function meta(string $key, $default=null);

	public function allMetas();

	public function addMeta(string $key, $value): self;
	
}

/**
 * 元数据
 * 
 * @author WZZ
 */
trait MetasFeature {

    /**
     * @var array
     */
    protected $metas = [];

	public function meta(string $key, $default=null) {
		return Arr::get($this->metas, strtolower($key), $default);
	}

	public function addMeta(string $key, $value): self {
		Arr::set($this->metas, strtolower($key), $value);
		return $this;
	}

	public function allMetas() {
		return $this->metas;
	}
	
}
