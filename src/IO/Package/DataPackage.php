<?php
namespace Swooen\IO\Package;

use Swooen\IO\Package\Features\DataArray;
use Swooen\IO\Package\Features\DataArrayFeature;

class DataPackage implements Package, DataArray {
	use DataArrayFeature;

	public function __construct(array $dataArr) {
		$this->dataArr = $dataArr;
	}

	
}
