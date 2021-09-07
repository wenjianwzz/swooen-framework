<?php
namespace Swooen\Communication;

/**
 * 空包
 */
class TerminatePackage implements Package {

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
