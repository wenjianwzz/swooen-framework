<?php
namespace Swooen\Data;


/**
 * @author wzz
 *
 */
class PBatisTransaction {

	/**
	 * @var \PDO
	 */
	protected $pdo;

	/**
	 * @var PBatis
	 */
	protected $batis;

	/**
	 * @param \PDO $pdo
	 */
	public function __construct($pdo, PBatis $pBatis) {
		$this->pdo = $pdo;
		$this->batis = $pBatis;
	}

	public function commit() {
		return $this->pdo->commit();
	}

	public function rollback() {
		return $this->pdo->rollBack();
	}

	public function beginTransaction() {
		$this->pdo->beginTransaction();
	}

	public function invoke($sqlKey, array $bindings) {
		$declare = $this->batis->get($sqlKey);
		$statement = $declare['statement'];
		$fetchMode = isset($declare['fetchMode'])?$declare['fetchMode']:[\PDO::FETCH_ASSOC];
		$action = $declare['action'];
		return call_user_func([$this, $action], $statement, $bindings, $fetchMode);
	}

	public function row($sqlKey, array $bindings) {
		$ret = $this->invoke($sqlKey, $bindings);
		return $ret?reset($ret):null;
	}

	public function select($statement, array $bindings, $fetchMode=[\PDO::FETCH_ASSOC]) {
		$prepared = $this->pdo->prepare($statement);
		$prepared->setFetchMode(...$fetchMode);
		$this->bindValues($prepared, $bindings);
		$prepared->execute();
		return $prepared->fetchAll();
	}

	public function update($statement, array $bindings) {
		$prepared = $this->pdo->prepare($statement);
		$this->bindValues($prepared, $bindings);
		$prepared->execute();
		return $prepared->rowCount();
	}

	public function insert($statement, array $bindings) {
		$prepared = $this->pdo->prepare($statement);
		$this->bindValues($prepared, $bindings);
		$prepared->execute();
		return $prepared->rowCount();
	}


    /**
     * Bind values to their parameters in the given statement.
     *
     * @param  \PDOStatement  $statement
     * @param  array  $bindings
     * @return void
     */
    public function bindValues($statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            $statement->bindValue(
                is_string($key) ? $key : $key + 1, $value,
                is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR
            );
        }
    }

	public function __destruct() {
		$this->batis->getPool()->returnback($this->pdo);
	}
}
