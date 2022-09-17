<?php
namespace Swooen\Communication;

use Swooen\Util\Arr;

class BasicPackage implements Package {

	protected $inputs;

	protected $metas;

	public function __construct(array $inputs, array $metas = []) {
		$this->inputs = $inputs;
		$this->metas = $metas;
	}

	public function input(string $key, $default=null) {
		return Arr::get($this->inputs, $key, $default);
	}

	public function meta(string $key, $default=null) {
		return Arr::get($this->metas, strtolower($key), $default);
	}

	public function inputs() {
		return $this->inputs;
	}

	public function metas() {
		return $this->metas;
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
