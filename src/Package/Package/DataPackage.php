<?php
namespace Swooen\Package\Package;

use Swooen\Package\Package\Features\DataArray;
use Swooen\Package\Package\Features\DataArrayImpl;
use Swooen\Util\Arr;

class DataPackage implements Package, DataArray {
	use DataArrayImpl;

	public function __construct(array $dataArr) {
		$this->dataArr = $dataArr;
	}

	
}
