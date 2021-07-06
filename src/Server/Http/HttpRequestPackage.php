<?php
namespace Swooen\Server\Http;

use Illuminate\Support\Arr;
use Swooen\Communication\BasicPackage;
use Swooen\Communication\IPAwarePackage;
use Swooen\Communication\RouteablePackage;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class HttpRequestPackage extends BasicPackage implements RouteablePackage, IPAwarePackage {

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request, $parsedBody) {
		$this->request = $request;
		$this->parsedBody = $parsedBody;
		$params = array_merge($request->request->all(), $request->query->all());
		$this->inputs = array_merge($params, $parsedBody);
		$this->metas = $request->headers->all() + ['http-method' => $this->request->getMethod()];
		$this->cookies = $request->cookies->all();
	}

	public function getRoutePath() {
		return $this->request->getPathInfo();
	}

	public function getIP() {
		return $this->request->getClientIp();
	}
	
}
