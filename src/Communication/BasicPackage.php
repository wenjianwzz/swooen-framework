<?php
namespace Swooen\Communication;

use Illuminate\Support\Arr;

class BasicPackage implements Package {

	protected $inputs;

	protected $metas;

	protected $cookies;

	public function __construct(array $inputs, array $metas, array $cookies) {
		$this->inputs = $inputs;
		$this->metas = $metas;
		$this->cookies = $cookies;
	}

	public function input(string $key, $default=null) {
		return Arr::get($this->inputs, $key, $default);
	}

	public function meta(string $key, $default=null) {
		return Arr::get($this->metas, $key, $default);
	}

	public function inputs() {
		return $this->inputs;
	}

	public function metas() {
		return $this->metas;
	}

	public function getType() {
		return self::TYPE_ARRAY;
	}

	public function raw() {
		return false;
	}

	public function cookie($key, $default=null) {
		return Arr::get($this->cookies, $key, $default);
	}

	public function cookies() {
		return $this->cookies;
	}
	
}
