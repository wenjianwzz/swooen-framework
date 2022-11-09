<?php
namespace Swooen\IO\Package\Features;

use Swooen\Util\Arr;

/**
 * 元数据
 * 
 * @author WZZ
 */
interface DataArray {

	public function getData(string $key, $default=null);

	public function allData();
	
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

	public function allData() {
		return $this->dataArr;
	}
	
}
