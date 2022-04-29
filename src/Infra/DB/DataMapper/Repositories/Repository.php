<?php

namespace AppFinance\Infra\DB\DataMapper\Repositories;

use AppFinance\Infra\DB\DataMapper\Drivers\DriverInterface;
use AppFinance\Infra\DB\DataMapper\QueryBuilder\Delete;
use AppFinance\Infra\DB\DataMapper\QueryBuilder\Insert;
use AppFinance\Infra\DB\DataMapper\QueryBuilder\Select;
use AppFinance\Infra\DB\DataMapper\QueryBuilder\Update;
use AppFinance\Shared\Entity;

abstract class Repository
{
    protected $driver;
    protected $has_created_at = true;
    protected $has_updated_at = true;
    protected ?string $table;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function insert(Entity $entity): ?bool
    {
        $fields = $this->mapEntityToArrayFields($entity);
        $this->addCreatedAt($fields);
        $this->addUpdatedAt($fields);
        $this->driver->setQueryBuilder(new Insert($this->table, $fields));
        return $this->driver->execute();
    }

    public function delete(string $id): bool
    {
        $condition = [
            ['id', $id]
        ];
        $this->driver->setQueryBuilder(new Delete($this->table, $condition));
        //return $this->driver->execute();
        $this->driver->execute();
        return $this->driver->rowsAffected() > 0;
    }

    public function update(Entity $entity): ?bool
    {
        $condition = [
            ['id', $entity->getId()]
        ];
        $fields = $this->mapEntityToArrayFields($entity);
        $this->addUpdatedAt($fields);
        $this->driver->setQueryBuilder(new Update($this->table, $fields, $condition));
        return $this->driver->execute();
    }

    public function first(?string $id = null): ?Entity
    {
        $condition = [];
        if (! empty($id)) {
            $condition[] = ['id', $id];
        }
        $this->driver->setQueryBuilder(new Select($this->table, $condition));
        $this->driver->execute();
        $first = $this->driver->first();
        if (!$first){
            return null;
        }
        return $this->makeEntity($first);
    }

    public function all(array $condition = []): ?Array
    {
        $this->driver->setQueryBuilder(new Select($this->table, $condition));
        $this->driver->execute();
        $all = $this->driver->all();
        $entities = [];
        foreach ($all as $line) {
            $entities[] = $this->makeEntity($line);
        }
        return $entities;
    }

    private function addCreatedAt(&$fields)
    {
        if (! $this->has_created_at) {
            return;
        }
        if (! array_key_exists('created_at', $fields)) {
            $created = new \DateTime('now', new \DateTimeZone("UTC"));
            $fields['created_at'] = $created ->format('Y-m-d H:i:s');
        }
    }

    private function addUpdatedAt(&$fields)
    {
        if (! $this->has_updated_at) {
            return;
        }
        if (! array_key_exists('updated_at', $fields)) {
            $updated = new \DateTime('now', new \DateTimeZone("UTC"));
            $fields['updated_at'] = $updated ->format('Y-m-d H:i:s');
        }
    }

    protected abstract function makeEntity(array $fields): ?Entity;
    protected abstract function mapEntityToArrayFields(Entity $entity): array;
    
}