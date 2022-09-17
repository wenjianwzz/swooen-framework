<?php
namespace Swooen\Runtime\Swoole\Redis;

/**
 * @author WZZ
 */
class RedisCommandParser {

	public function packCommand($ip, $cmd, $data) {
		return new RedisCommandPackage($ip, $cmd, $data);
	}
}
