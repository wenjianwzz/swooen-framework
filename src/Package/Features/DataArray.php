<?php
namespace Swooen\Package\Features;

use Wenjianwzz\Tool\Util\Arr;

/**
 * 元数据
 * 
 * @author WZZ
 */
interface DataArray {

	public function getData(string $key, $default=null);

	public function allData();

	public function addData(string $key, $value): self;
	
}

/**
 * 元数据
 * 
 * @author WZZ
 */
trait DataArrayFeature {

    /**
     * @var array
     */
    protected $dataArr;

	public function getData(string $key, $default=null) {
		return Arr::get($this->dataArr, strtolower($key), $default);
	}

	public function addData(string $key, $value): self {
		Arr::set($this->dataArr, strtolower($key), $value);
		return $this;
	}

	public function allData() {
		return $this->dataArr;
	}
	
}
