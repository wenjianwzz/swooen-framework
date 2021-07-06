<?php
namespace Swooen\Server\Http;

use Swooen\Communication\ConnectionFactory;
use Swooen\Communication\Writer;
use Swooen\Server\Http\Parser\JsonParser;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class GlobalToConnectionFactory implements ConnectionFactory {
	
	public function make() {
		$connection = new Connection();
		$connection->registerContentParser(new JsonParser());
		$connection->instance(Writer::class, $connection);
		return $connection;
	}

}
