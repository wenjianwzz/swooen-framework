<?php
namespace Swooen\Batis\Pool;

use Psr\Log\LoggerInterface;
use Swoole\Coroutine\Channel;

class SwoolePool extends SimplePool {

    /**
     * @var Channel
     */
    protected $pool;

    public function __construct(PDOConfig $config, ?LoggerInterface $logger=null, $size=8) {
        parent::__construct($config, $logger);
        $this->pool = new Channel($size);
    }
    
    public function prefill() {
        for($i=0; $i<$this->pool->capacity; ++$i) {
            $this->pool->push($this->create(), 0.01);
        }
    }

	protected function _log($message, $context=[]) {
		if ($this->logger) {
			$this->logger->debug('[SwoolePool] '.$message, $context);
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
        $pdo = $this->pool->pop(1); 
        if ($pdo && $this->checkPDO($pdo)) {
            $this->_log('got PDO[id='. spl_object_id($pdo) .'] from pool');
            return $pdo;
        }
        return $this->create();
    }

    public function returnback($pdo) {
        if (-1 != \Swoole\Coroutine::getCid()) {
            $this->_log('try return PDO[id='. spl_object_id($pdo) .'] to pool');
            if (!$this->pool->isFull()) {
                if ($this->pool->push($pdo, 0.01)) {
                    $this->_log('return PDO[id='. spl_object_id($pdo) .'] to pool');
                }
            }
        }
    }
}
