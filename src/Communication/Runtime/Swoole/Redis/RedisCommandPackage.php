<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\BasicPackage;
use Swooen\Communication\IPAwarePackage;
use Swooen\Communication\RouteablePackage;

/**
 * 封装来往通信报文
 * 
 * @author WZZ
 */
class RedisCommandPackage extends BasicPackage implements RouteablePackage, IPAwarePackage {

	const KEY_COMMAND = 'command';

	const KEY_DATA = 'data';

	public function __construct($ip, $command, $data) {
		$this->inputs = [
			self::KEY_COMMAND => $command,
			self::KEY_DATA => $data,
		];
		$this->metas = [
			'ip' => $ip
		];
	}

	public function getCommand() {
		return $this->input(self::KEY_COMMAND);
	}

	public function getRoutePath() {
		return strtolower($this->getCommand());
	}

	public function getIP() {
		return $this->meta('ip');
	}

}
