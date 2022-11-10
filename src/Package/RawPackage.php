<?php
namespace Swooen\Package;

use Swooen\Package\Features\RawData;
use Swooen\Package\Features\RawDataFeature;

class RawPackage implements Package, RawData {
	use RawDataFeature;

	public function __construct(string $rawData) {
		$this->rawData = $rawData;
	}

	
}
