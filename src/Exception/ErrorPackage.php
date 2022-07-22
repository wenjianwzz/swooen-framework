<?php
namespace Swooen\Exception;

use Swooen\Util\Arr;
use Swooen\Communication\BasicPackage;

class ErrorPackage extends BasicPackage {

	public function __construct($errmsg) {
		parent::__construct(['errmsg' => $errmsg], []);
	}
	
}
