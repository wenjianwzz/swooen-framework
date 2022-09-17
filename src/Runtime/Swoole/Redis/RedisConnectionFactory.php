<?php
namespace Swooen\Runtime\Swoole\Redis;

use Swooen\Communication\Writer;
use Swooen\Runtime\Swoole\SwooleConnectionFactory;
use \Swoole\Redis\Server;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class RedisConnectionFactory extends SwooleConnectionFactory {

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var RedisConnection[]
	 */
	protected $connections = [];

	/**
	 * @var RedisCommandParser
	 */
	protected $parser;

	public function __construct($host, $port) {
		$this->server = new Server($host, $port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
		$this->initHandler();
		$this->initOnClose();
		$this->setParser($this->createParser());
	}

	/**
	 * @return RedisCommandParser
	 */
	public function createParser() {
		return new RedisCommandParser();
	}

	/**
	 * @return RedisCommandParser
	 */
	public function getParser() {
		return $this->parser;
	}

	/**
	 * Set the value of parser
	 */
	public function setParser(RedisCommandParser $parser): self {
		$this->parser = $parser;
		return $this;
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
		$connection = new RedisConnection($this->server, $this, $fd);
		$connection->instance(Writer::class, $this->createWriter($fd));
		return $connection;
	}
	
	protected function initHandler() {
		foreach (self::COMMANDS as $cmd) {
			$this->server->setHandler($cmd, function($fd, $data) use ($cmd) {
				if (!isset($this->connections[$fd])) {
					$connection = $this->createConnection($fd);
					$this->connections[$fd] = $connection;
					go(function() use ($connection) {
						($this->callback)($connection);
					});
				} else {
					$connection = $this->connections[$fd];
				}
				$info = $this->server->getClientInfo($fd);
				$ip = isset($info['remote_ip'])?$info['remote_ip']:'';
				$connection->dispatchPackage($this->parser->packCommand($ip, $cmd, $data));
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
