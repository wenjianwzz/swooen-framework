<?php
namespace Swooen\Package\Package;

use Swooen\Package\Package\Features\DataArray;
use Swooen\Package\Package\Features\DataArrayFeature;

class DataPackage implements Package, DataArray {
	use DataArrayFeature;

	public function __construct(array $dataArr) {
		$this->dataArr = $dataArr;
	}

	
}
