<?php
namespace Swooen\Exception;

use Wenjianwzz\Tool\Util\Arr;
use Swooen\IO\BasicPackage;

class ErrorPackage extends BasicPackage {

	public function __construct($errmsg) {
		parent::__construct(['errmsg' => $errmsg], []);
	}
	
}
