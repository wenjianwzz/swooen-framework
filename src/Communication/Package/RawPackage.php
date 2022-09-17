<?php
namespace Swooen\Communication;

use Swooen\Util\Arr;

class RawPackage extends BasicPackage {

	protected $content;

	public function __construct($content, array $metas = []) {
		parent::__construct([], $metas);
		$this->content = $content;
	}

	public function isArray() {
		return false;
	}

	public function isString() {
		return true;
	}

	public function getString() {
		return $this->content;
	}
	
}
