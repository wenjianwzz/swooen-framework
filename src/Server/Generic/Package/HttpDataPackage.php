<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Package\Package;
use Swooen\Util\Arr;

/**
 * @author WZZ
 */
class HttpDataPackage extends HttpRequestPackage {

	public function __construct(\Symfony\Component\HttpFoundation\Request $request, array $data) {
		parent::__construct($request);
		$this->dataArr = array_merge($this->dataArr, $data);
	}
}
