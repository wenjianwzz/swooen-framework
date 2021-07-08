<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\ConnectionFactory;
use Swooen\Communication\Reader;
use Swooen\Communication\Writer;
use Swooen\Server\Http\Reader\HttpReader;
use Swooen\Server\Http\Writer\JsonWriter;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class RedisConnectionFactory implements ConnectionFactory {
	
	public function make() {
		$connection = new RedisConnection();
		$reader = new HttpReader();
		$connection->instance(Writer::class, new JsonWriter());
		$connection->instance(Reader::class, $reader);
		return $connection;
	}

}
