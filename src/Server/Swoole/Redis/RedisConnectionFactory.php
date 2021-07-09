<?php
namespace Swooen\Server\Swoole\Redis;

use Swooen\Communication\ConnectionFactory;
use Swooen\Communication\Reader;
use Swooen\Communication\Writer;
use \Swoole\Redis\Server;

/**
 * 封装各种类型协议，负责监听通讯，将请求统一成固定格式
 * 
 * @author WZZ
 */
class RedisConnectionFactory implements ConnectionFactory {

	protected $server;

	protected $callback;

	/**
	 * @var RedisConnection[]
	 */
	protected $connections = [];

	public function __construct($host, $port, $mode=SWOOLE_BASE, $sockType=SWOOLE_SOCK_TCP) {
		$this->server = new Server($host, $port, $mode, $sockType);
		foreach (self::COMMANDS as $cmd) {
			$this->server->setHandler($cmd, function($fd, $data) use ($cmd) {
				if (!isset($this->connections[$fd])) {
					$info = $this->server->getClientInfo($fd);
					$ip = isset($info['remote_ip'])?$info['remote_ip']:'';
					$connection = new RedisConnection();
					$connection->instance(Reader::class, new RedisCommandReader($ip));
					$connection->instance(Writer::class, new RedisWriter($this->server, $fd));
					$connection->instance(\Swooen\Exception\Handler::class, new RedisExceptionHandler());
					$this->connections[$fd] = $connection;
					go(function() use ($connection, $fd) {
						var_dump('=> new conntection: '. $fd);
						($this->callback)($connection);
						var_dump('=> finish conntection: '. $fd);
					});
				} else {
					$connection = $this->connections[$fd];
				}
				$reader = $connection->getReader();
				assert($reader instanceof RedisCommandReader);
				$reader->queueCommand($cmd, $data);
			});
		}
		$this->server->on('close', function($server, $fd, $reactorId) {
			if (isset($this->connections[$fd])) {
				$connection = $this->connections[$fd];
				$connection->setPairLeaved();
				$reader = $connection->getReader();
				assert($reader instanceof RedisCommandReader);
				$reader->queueCommand('BYEBYE', []);
				unset($this->connections[$fd]);
				$connection->destroy();
				unset($connection);
			}
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
