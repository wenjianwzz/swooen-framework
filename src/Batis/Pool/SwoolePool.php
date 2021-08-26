<?php
namespace Swooen\Batis\Pool;
use Swoole\Coroutine\Channel;

class SwoolePool extends SimplePool {

    /**
     * @var Channel
     */
    protected $pool;

    public function __construct(PDOConfig $config, $size=4, $prefill=false) {
        parent::__construct($config);
        $this->pool = new Channel($size);
        if ($prefill) {
            for($i=0; $i<$size; ++$i) {
                $this->returnback($this->create());
            }
        }
    }

    public function checkPDO($pdo) {
        if ($pdo instanceof \PDO) {
            try {
                $pdo->query('select 1');
            } catch (\Throwable $t) {
                echo 'PDO dead' . $t->getMessage() . PHP_EOL;
                return false;
            }
            return true;
        }
        return false;
    }

    public function get() {
        // 先等待，如果没有，创建新的
        $pdo = $this->pool->pop(0.2); 
        if ($pdo && $this->checkPDO($pdo)) {
            return $pdo;
        }
        return $this->create();
    }

    public function returnback($pdo) {
        $this->pool->push($pdo, 0.1);
    }
}
