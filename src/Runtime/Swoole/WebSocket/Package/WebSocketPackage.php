<?php
namespace Swooen\Runtime\Swoole\WebSocket\Package;

use Swooen\Package\BasicPackage;
use Swooen\Package\IPAwarePackage;
use Swooen\Package\RouteablePackage;

/**
 * @author WZZ
 */
class WebSocketPackage extends BasicPackage implements RouteablePackage, IPAwarePackage {
    
	protected $path;

	protected $content;

	protected $ip;

	public function __construct($path, $inputs, $metas, $content, $ip) {
		$this->path = $path;
		$this->content = $content;
		$this->inputs = $inputs;
		$this->metas = $metas;
		$this->ip = $ip;
	}

	public function isArray() {
		return true;
	}

	public function isString() {
		return true;
	}

	public function getString() {
		return $this->content;
	}

	public function getRoutePath() {
		return $this->path;
	}

	public function getIP() {
		return $this->ip;
	}
}
