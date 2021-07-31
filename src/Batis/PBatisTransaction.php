<?php
namespace Swooen\Batis;


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
	 * 事务计数
	 */
	protected $transactionCounter = 0;

	/**
	 * @param \PDO $pdo
	 */
	public function __construct(PBatis $pBatis) {
		$this->batis = $pBatis;
		$this->pdo = $pBatis->getPool()->get();
	}

    public function beginTransaction() {
        if (!$this->transactionCounter++) {
            return $this->pdo->beginTransaction();
        }
        $this->pdo->exec('SAVEPOINT trans'.$this->transactionCounter);
        return $this->transactionCounter >= 0;
    }

    public function commit() {
        if (!--$this->transactionCounter) {
            return $this->pdo->commit();
        }
        return $this->transactionCounter >= 0;
    }

    public function rollback() {
        if (--$this->transactionCounter) {
            $this->pdo->exec('ROLLBACK TO trans'.$this->transactionCounter + 1);
            return true;
        }
        return $this->pdo->rollBack();
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

	public function affectingStatement($statement, $bindings = []) {
		$prepared = $this->pdo->prepare($statement);
		$this->bindValues($prepared, $bindings);
		$prepared->execute();
		return $prepared->rowCount();
	}

	public function update($statement, array $bindings = []) {
		return $this->affectingStatement($statement, $bindings);
	}

	public function insert($statement, array $bindings = []) {
		return $this->affectingStatement($statement, $bindings);
	}

	public function delete($statement, array $bindings = []) {
		return $this->affectingStatement($statement, $bindings);
	}

	public function insertGetId($statement, array $bindings) {
		$this->insert($statement, $bindings);
		return $this->pdo->lastInsertId();
	}

	public function insertRows($table, $rows) {
		if (empty($rows)) {
			return 0;
		}
		$fields = array_keys(reset($rows));
		$fieldsClause = join('`, `', $fields);
		$clause = join(',', array_fill(0, count($fields), '?'));
		$clauses = join('), (', array_map(function() use ($clause) {return $clause;}, $rows));
		$sql = "insert into `{$table}` (`{$fieldsClause}`) values ({$clauses});";
		$binds = array_reduce($rows, function($ret, $row) {
			return array_merge($ret, array_values($row));
		}, []);
		return $this->insert($sql, $binds);
	}

	public function insertRowGetId($table, $row) {
		if (empty($row)) {
			return false;
		}
		$fields = array_keys($row);
		$fieldsClause = join('`, `', $fields);
		$clause = join(',', array_fill(0, count($fields), '?'));
		$sql = "insert into `{$table}` (`{$fieldsClause}`) values ({$clause});";
		return $this->insertGetId($sql, array_values($row));
	}

	public function updateRow($table, $info, $where) {
		$infoKeys = [];
		$infoValues = [];
		foreach( $info as $key=>$val ) {
			array_push($infoKeys, "`{$key}`=?");
			array_push($infoValues, $val);
		}
		$whereKeys = [];
		$whereValues = [];
		foreach( $where as $key=>$val ) {
			array_push($whereKeys, "`{$key}`=?");
			array_push($whereValues, $val);
		}
		$fieldsClause = join(',', $infoKeys);
		$whereClause = join(' and ', $whereKeys);
		$sql = "update `{$table}` set $fieldsClause where {$whereClause}";
		$binds = array_merge($infoValues, $whereValues);
		return $this->update($sql, $binds);
	}

	public function selectWhere($table, $where) {
		$whereKeys = [];
		$whereValues = [];
		foreach( $where as $key=>$val ) {
			array_push($whereKeys, "`{$key}`=?");
			array_push($whereValues, $val);
		}
		$whereClause = join(' and ', $whereKeys);
		$sql = "select * from `{$table}` where {$whereClause}";
		return $this->select($sql, $whereValues);
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
