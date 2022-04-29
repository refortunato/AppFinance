<?php

namespace AppFinance\Infra\DB\DataMapper\QueryBuilder;

use AppFinance\Infra\DB\DataMapper\QueryBuilder\Filters\Where;

class Update implements QueryBuilderInterface
{
    use Where;

    private $query;
    protected $values = [];

    public function __construct(string $table, array $data, array $conditions = [])
    {
        $this->values = array_values($data);
        $this->query = $this->makeSql($table, $data, $conditions);
    }

    private function makeSql(string $table, array $data, array $conditions = [])
    {
        $sql = sprintf('update %s', $table);
        $columns = array_keys($data);

        $columns_query = [];
        foreach ($columns as $column) {
            $columns_query[] = $column . '=?';
        }

        $sql .= ' set '. implode(', ', $columns_query);

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