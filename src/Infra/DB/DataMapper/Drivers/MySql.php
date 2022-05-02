<?php

namespace AppFinance\Infra\DB\DataMapper\Drivers;

use AppFinance\Infra\DB\DataMapper\Drivers\DriverInterface;
use AppFinance\Infra\DB\DataMapper\QueryBuilder\QueryBuilderInterface;

class MySql implements DriverInterface
{
    private \PDO $pdo;
    private $query;
    private int $rows_affected;

    public function connect($config)
    {
        if (empty($config['server'])) {
            throw new \InvalidArgumentException('server is required');
        }
        if (empty($config['database'])) {
            throw new \InvalidArgumentException('database is required');
        }
        if (empty($config['user'])) {
            throw new \InvalidArgumentException('user is required');
        }
        $dsn_pattern = 'mysql:host=%s;dbname=%s;charset=utf8';
        $dsn = sprintf($dsn_pattern, $config['server'], $config['database']);
        $user = $config['user'];
        $password = $config['password'] ?? null;
        $options = $config['options'] ?? [];

        $this->pdo = new \PDO($dsn, $user, $password, $options);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->rows_affected = 0;
    }

    public function close()
    {
        $this->pdo = null;
    }

    public function setQueryBuilder(QueryBuilderInterface $query)
    {
        $this->query = $query;
    }

    public function execute()
    {
        $this->sth = $this->pdo->prepare((string)$this->query);
        $result = $this->sth->execute($this->query->getValues());
        $this->rows_affected = $this->sth->rowCount();
        return $result;
    }

    public function rowsAffected(): int
    {
        return $this->rows_affected;
    }

    public function executeSelectFromText(string $query, array $params = [])
    {
        $sth = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $sth->bindParam($key, $value);
        }
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lastInsertedId()
    {
        //return $this->pdo->lastInsertedId();
    }

    public function first()
    {
        return $this->sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function all()
    {
        return $this->sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

}