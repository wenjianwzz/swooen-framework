<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\DataArrayFeature;
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
class HttpRequestPackage implements Package, RemoteAddressAware, RawData, DataArray, Metas, Routeable {
	use RemoteAddressAwareFeature, DataArrayFeature, MetasFeature, RouteableFeature, RawDataFeature;

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request) {
		$this->request = $request;
		$this->rawData = $request->getContent();
		$this->dataArr = array_merge($request->request?$request->request->all():[], $request->query?$request->query->all():[]);
		$this->metas = array_map('reset', $request->headers->all()) + ['http-method' => $this->request->getMethod()];
		$this->remoteAddress = $this->request->getClientIp();
		$this->routePath = strtoupper($this->request->getMethod()).' '.str_replace('//', '/', $this->request->getPathInfo());
	}
	
}
