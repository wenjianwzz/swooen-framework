<?php
namespace Swooen\Communication;

use Illuminate\Support\Arr;

class RawPackage implements Package {

	protected $content;

	public function __construct($content)
	{
		$this->content = $content;
	}

	public function input(string $key, $default=null) {
		return null;
	}

	public function meta(string $key, $default=null) {
		return null;
	}

	public function inputs() {
		return [];
	}

	public function metas() {
		return [];
	}

	public function getType() {
		return self::TYPE_RAW;
	}

	public function raw() {
		return $this->content;
	}

	public function cookie($key, $default=null) {
		return null;
	}

	public function cookies() {
		return [];
	}
	
}
