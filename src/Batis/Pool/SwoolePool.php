<?php
namespace Swooen\Batis\Pool;

use Psr\Log\LoggerInterface;
use Swoole\Coroutine\Channel;

class SwoolePool extends SimplePool {

    /**
     * @var Channel
     */
    protected $pool;

    /**
     * 等待时间
     */
    protected $waitTime;

    public function __construct(PDOConfig $config, ?LoggerInterface $logger=null, $size=8, $waitTime=5) {
        parent::__construct($config, $logger);
        $this->pool = new Channel($size);
        $this->waitTime = $waitTime;
    }
    
    public function prefill() {
        for($i=0; $i<$this->pool->capacity; ++$i) {
            $this->pool->push($this->create(), 0.01);
        }
    }

	protected function _log($message, $context=[]) {
		if ($this->logger) {
			$this->logger->debug('[SwoolePool] '.$message, array_merge($context, ['cid' => \Swoole\Coroutine::getCid()]));
		}
	}

    public function checkPDO($pdo) {
        if ($pdo instanceof \PDO) {
            try {
                $pdo->query('select 1');
            } catch (\Throwable $t) {
                $this->_log('PDO[id='. spl_object_id($pdo) .'] dead, drop');
                return false;
            }
            return true;
        }
        return false;
    }

    public function get() {
        // 先等待，如果没有，创建新的
        $this->_log('getting PDO');
        $pdo = $this->pool->pop($this->waitTime);
        if ($pdo && $this->checkPDO($pdo)) {
            $this->_log('got PDO[id='. spl_object_id($pdo) .'] from pool');
            return $pdo;
        }
        return $this->create();
    }

    public function returnback($pdo) {
        $pdoId = spl_object_id($pdo);
        if (-1 != \Swoole\Coroutine::getCid()) {
            if (!$this->pool->isFull()) {
                if ($this->pool->push($pdo, 0.01)) {
                    $this->_log('return PDO[id='. $pdoId .'] to pool');
                }
            } else {
                $this->_log('drop PDO[id='. $pdoId .'], pool is full');
            }
        }
    }
}
