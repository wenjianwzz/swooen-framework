<?php
namespace Swooen\Server\Legacy;

use Illuminate\Support\Arr;
use Swooen\Communication\ArrayPackage;
use Swooen\Communication\RouteablePackage;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class HttpRequestPackage extends ArrayPackage implements RouteablePackage {

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request, $parsedBody) {
		$this->request = $request;
		$this->parsedBody = $parsedBody;
		$params = array_merge($request->request->all(), $request->query->all());
		$this->inputs = array_merge($params, $parsedBody);
		$this->metas = $request->headers->all();
	}

	public function getRoutePath() {
		return $this->request->getMethod().' '.$this->request->getPathInfo();
	}
	
}
