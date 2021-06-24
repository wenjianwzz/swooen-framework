<?php
namespace Swooen\Server\Legacy;

use Swooen\Communication\ConnectionFactory;
use Swooen\Server\Legacy\Parser\JsonParser;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class GlobalToConnectionFactory implements ConnectionFactory {
	
	public function make() {
		$connection = new Connection();
		$connection->registerContentParser(new JsonParser());
		return $connection;
	}

}
