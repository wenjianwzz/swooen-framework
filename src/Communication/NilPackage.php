<?php
namespace Swooen\Communication;

use Swooen\Util\Arr;

/**
 * 空包
 */
class NilPackage implements Package {

	public function input(string $key, $default=null) {
		return $default;
	}

	public function meta(string $key, $default=null) {
		return $default;
	}

	public function inputs() {
		return [];
	}

	public function metas() {
		return [];
	}

	public function isArray() {
		return true;
	}

	public function isString() {
		return false;
	}

	public function getString() {
		return '';
	}
	
}
