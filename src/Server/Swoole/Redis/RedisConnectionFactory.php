<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\Reader;
use Swooen\Communication\Writer;
use Swooen\Server\Swoole\SwooleConnectionFactory;
use \Swoole\Redis\Server;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class RedisConnectionFactory extends SwooleConnectionFactory {

	protected $server;

	protected $callback;

	/**
	 * @var RedisConnection[]
	 */
	protected $connections = [];

	/**
	 * @return RedisCommandReader
	 */
	public function createReader($fd) {
		$info = $this->server->getClientInfo($fd);
		$ip = isset($info['remote_ip'])?$info['remote_ip']:'';
		return new RedisCommandReader($ip);
	}

	/**
	 * @return RedisWriter
	 */
	public function createWriter($fd) {
		return new RedisWriter($this->server, $fd);
	}

	/**
	 * @return RedisConnection
	 */
	public function createConnection($fd) {
		return new RedisConnection($this->server, $this, $fd);
	}

	public function __construct($host, $port, $mode=SWOOLE_BASE, $sockType=SWOOLE_SOCK_TCP) {
		$this->server = new Server($host, $port, $mode, $sockType);
		$this->initOnRequest();
		$this->initOnClose();
	}
	
	protected function initOnRequest() {
		$this->server->on('request', function ($request, $response) {
			$response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
		});
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
