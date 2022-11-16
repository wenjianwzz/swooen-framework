<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Package\Features\HttpStatusAware;
use Swooen\Package\Features\HttpStatusAwareFeature;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\MetasFeature;
use Swooen\Package\Features\RawData;
use Swooen\Package\Features\RawDataFeature;
use Swooen\Package\Package;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class HttpResponsePackage implements Package, RawData, Metas, HttpStatusAware {
	use RawDataFeature, MetasFeature, HttpStatusAwareFeature;

	public function __construct(string $data) {
		$this->rawData = $data;
	}
	
}
