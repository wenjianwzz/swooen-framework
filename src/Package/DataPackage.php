<?php
namespace Swooen\Package;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\DataArrayFeature;

class DataPackage implements Package, DataArray {
	use DataArrayFeature;

	public function __construct(array $dataArr) {
		$this->dataArr = $dataArr;
	}

	
}
