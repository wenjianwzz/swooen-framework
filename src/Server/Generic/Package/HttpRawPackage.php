<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Package\Features\RawData;
use Swooen\Package\Features\RawDataFeature;
use Swooen\Package\Package;
use Swooen\Util\Arr;

/**
 * @author WZZ
 */
class HttpRawPackage extends HttpRequestPackage implements RawData {
	use RawDataFeature;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request, string $raw) {
		parent::__construct($request);
		$this->rawData = $raw;
	}
}
