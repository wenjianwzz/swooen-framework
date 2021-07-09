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

	protected $server;

	protected $callback;

	public function __construct($host, $porrt, $mode=SWOOLE_BASE, $sockType=NULL) {
		$this->server = new \Swoole\Redis\Server($host, $porrt, $mode, $sockType);
		foreach (self::COMMANDS as $cmd) {
			$this->server->setHandler($cmd, function($fd, $data) {
				var_dump($data);
			});
		}
	}
		
	public function onConnection(callable $callback) {
		$this->callback = $callback;
	}
	
	public function start() {
		$this->server->start();
	}

	const COMMANDS = [
		'DEL',
		'DUMP',
		'EXISTS',
		'EXPIRE',
		'EXPIREAT',
		'KEYS',
		'MIGRATE',
		'MOVE',
		'OBJECT',
		'PERSIST',
		'PEXPIRE',
		'PEXPIREAT',
		'PTTL',
		'RANDOMKEY',
		'RENAME',
		'RENAMENX',
		'RESTORE',
		'SORT',
		'TTL',
		'TYPE',
		'SCAN',
		'APPEND',
		'BITCOUNT',
		'BITOP',
		'DECR',
		'DECRBY',
		'GET',
		'GETBIT',
		'GETRANGE',
		'GETSET',
		'INCR',
		'INCRBY',
		'INCRBYFLOAT',
		'MGET',
		'MSET',
		'MSETNX',
		'PSETEX',
		'SET',
		'SETBIT',
		'SETEX',
		'SETNX',
		'SETRANGE',
		'STRLEN',
		'HDEL',
		'HEXISTS',
		'HGET',
		'HGETALL',
		'HINCRBY',
		'HINCRBYFLOAT',
		'HKEYS',
		'HLEN',
		'HMGET',
		'HMSET',
		'HSET',
		'HSETNX',
		'HVALS',
		'HSCAN',
		'BLPOP',
		'BRPOP',
		'BRPOPLPUSH',
		'LINDEX',
		'LINSERT',
		'LLEN',
		'LPOP',
		'LPUSH',
		'LPUSHX',
		'LRANGE',
		'LREM',
		'LSET',
		'LTRIM',
		'RPOP',
		'RPOPLPUSH',
		'RPUSH',
		'RPUSHX',
		'SADD',
		'SCARD',
		'SDIFF',
		'SDIFFSTORE',
		'SINTER',
		'SINTERSTORE',
		'SISMEMBER',
		'SMEMBERS',
		'SMOVE',
		'SPOP',
		'SRANDMEMBER',
		'SREM',
		'SUNION',
		'SUNIONSTORE',
		'SSCAN',
		'ZADD',
		'ZCARD',
		'ZCOUNT',
		'ZINCRBY',
		'ZRANGE',
		'ZRANGEBYSCORE',
		'ZRANK',
		'ZREM',
		'ZREMRANGEBYRANK',
		'ZREMRANGEBYSCORE',
		'ZREVRANGE',
		'ZREVRANGEBYSCORE',
		'ZREVRANK',
		'ZSCORE',
		'ZUNIONSTORE',
		'ZINTERSTORE',
		'ZSCAN',
		'PSUBSCRIBE',
		'PUBLISH',
		'PUBSUB',
		'PUNSUBSCRIBE',
		'SUBSCRIBE',
		'UNSUBSCRIBE',
		'DISCARD',
		'EXEC',
		'MULTI',
		'UNWATCH',
		'WATCH',
		'EVAL',
		'EVALSHA',
		'SCRIPT EXISTS',
		'SCRIPT FLUSH',
		'SCRIPT KILL',
		'SCRIPT LOAD',
		'AUTH',
		'ECHO',
		'PING',
		'QUIT',
		'SELECT',
		'BGREWRITEAOF',
		'BGSAVE',
		'CLIENT GETNAME',
		'CLIENT KILL',
		'CLIENT LIST',
		'CLIENT SETNAME',
		'CONFIG GET',
		'CONFIG RESETSTAT',
		'CONFIG REWRITE',
		'CONFIG SET',
		'DBSIZE',
		'DEBUG OBJECT',
		'DEBUG SEGFAULT',
		'FLUSHALL',
		'FLUSHDB',
		'INFO',
		'LASTSAVE',
		'MONITOR',
		'PSYNC',
		'SAVE',
		'SHUTDOWN',
		'SLAVEOF',
		'SLOWLOG',
		'SYNC',
		'TIME'
	];

}
