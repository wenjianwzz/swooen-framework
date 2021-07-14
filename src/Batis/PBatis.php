<?php
namespace Swooen\Batis;

use Swooen\Batis\Pool\PDOPool;

/**
 * 仿照iBatis设计的数据库访问类
 * @author wzz
 *
 */
class PBatis {

	/**
	 * @var PDOPool
	 */
	protected $pool;

	protected $sqls = [];

	public function __construct(PDOPool $pool) {
		$this->pool = $pool;
	}

	public function loadDict(array $sqlDict) : self {
		$this->sqls = array_merge($this->sqls, $sqlDict);
		return $this;
	}

    public function get($sqlKey) {
		if (isset($this->sqls[$sqlKey])) {
            return $this->sqls[$sqlKey];
		}
		throw new \RuntimeException('指定的SQL Key不存在');
    }

    public function transaction() {
        return new PBatisTransaction($this->pool->get(), $this);
    }

	/**
	 * @return PDOPool
	 */
	public function getPool() {
		return $this->pool;
	}
}
