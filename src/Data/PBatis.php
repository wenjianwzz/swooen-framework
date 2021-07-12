<?php
namespace Swooen\Data;

use Swooen\Data\Pool\PDOPool;

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

	public function loadDict(array $sqlDict) {
		$this->sqls = array_merge($this->sqls, $sqlDict);
	}

    public function get($sqlKey) {
		if (isset($this->sqls[$sqlKey])) {
            return $this->sqls[$sqlKey];
		}
		throw new \RuntimeException('指定的SQL Key不存在');
    }

    public function session() {
        return new PBatisSession($this->pool->get(), $this);
    }
}
