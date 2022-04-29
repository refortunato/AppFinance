<?php

namespace AppFinance\Infra\DB\DataMapper\QueryBuilder;

use AppFinance\Infra\DB\DataMapper\QueryBuilder\Filters\Where;

class Select implements QueryBuilderInterface
{
    use Where;

    private $query;
    protected $values = [];

    public function __construct(string $table, array $conditions = [])
    {
        $this->query = $this->makeSql($table, $conditions);
    }

    private function makeSql(string $table, array $conditions = [])
    {
        $sql = sprintf('select * from %s', $table);
        if ($conditions) {
            $sql .= $this->makeWhere($conditions);
        }
        return $sql;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function __toString()
    {
        return $this->query;
    }
}