<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\DataArrayFeature;
use Swooen\Package\Features\HttpStatusAware;
use Swooen\Package\Features\HttpStatusAwareFeature;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\MetasFeature;
use Swooen\Package\Features\RawData;
use Swooen\Package\Features\RawDataFeature;
use Swooen\Package\Features\RemoteAddressAware;
use Swooen\Package\Features\RemoteAddressAwareFeature;
use Swooen\Package\Features\Routeable;
use Swooen\Package\Features\RouteableFeature;
use Swooen\Package\Package;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class HttpResponsePackage implements Package, RawData, Metas, HttpStatusAware {
	use RemoteAddressAwareFeature, RawDataFeature, MetasFeature, RouteableFeature, HttpStatusAwareFeature;

	public function __construct(string $data) {
		$this->rawData = $data;
	}
	
}
