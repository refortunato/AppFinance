<?php

namespace AppFinance\Infra\DB\DataMapper\QueryBuilder;

interface QueryBuilderInterface
{
    public function getValues();
    public function __toString();
}