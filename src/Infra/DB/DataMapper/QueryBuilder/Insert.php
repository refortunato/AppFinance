<?php

namespace AppFinance\Infra\DB\DataMapper\QueryBuilder;

use AppFinance\Infra\DB\DataMapper\QueryBuilder\Filters\Where;

class Insert implements QueryBuilderInterface
{
    use Where;

    private $query;
    protected $values = [];

    public function __construct(string $table, array $data)
    {
        $this->query = $this->makeSql($table, $data);
        $this->values = array_values($data);
    }

    private function makeSql(string $table, array $data)
    {
        $sql = sprintf('insert into %s', $table);
        $columns = array_keys($data);
        $values = array_fill(0, count($data), '?');

        $columns = implode(', ', $columns);
        $values = implode(', ', $values);

        $sql.= sprintf(' (%s) values (%s)', $columns, $values);

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