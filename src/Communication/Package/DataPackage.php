<?php
namespace Swooen\Communication\Package;

use Swooen\Communication\Package\Features\DataArray;
use Swooen\Communication\Package\Features\DataArrayImpl;
use Swooen\Util\Arr;

class DataPackage implements Package, DataArray {
	use DataArrayImpl;

	public function __construct(array $dataArr) {
		$this->dataArr = $dataArr;
	}

	
}
