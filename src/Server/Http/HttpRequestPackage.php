<?php
namespace Swooen\Server\Http;

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

	protected $parsedBody;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request, $parsedBody) {
		$this->request = $request;
		$this->parsedBody = $parsedBody;
		$params = array_merge($request->request?$request->request->all():[], $request->query?$request->query->all():[]);
		$this->inputs = $parsedBody?array_merge($params, $parsedBody):$params;
		$this->metas = array_map('reset', $request->headers->all()) + ['http-method' => $this->request->getMethod()];
	}

	public function isArray() {
		return true;
	}

	public function isString() {
		return false === $this->parsedBody;
	}

	public function getString() {
		return $this->request->getContent();
	}

	public function getRoutePath() {
		return $this->request->getMethod().' '.str_replace('//', '/', $this->request->getPathInfo());
	}

	public function getIP() {
		return $this->request->getClientIp();
	}
	
}
