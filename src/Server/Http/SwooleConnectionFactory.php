<?php
namespace Swooen\Server\Http;

use Swooen\Communication\ConnectionFactory;
use Swooen\Server\Http\Parser\JsonParser;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class SwooleConnectionFactory implements ConnectionFactory {
	
	public function make() {
		$connection = new Connection();
		return $connection;
	}

}
