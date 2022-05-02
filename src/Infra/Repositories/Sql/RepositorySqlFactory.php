<?php

namespace AppFinance\Infra\Repositories\Sql;

use AppFinance\Infra\DB\DataMapper\Drivers\DriverFactory;
use AppFinance\Infra\DB\DataMapper\Drivers\DriverInterface;

class RepositorySqlFactory
{
    private static function getDriver(?DriverInterface $driver = null): DriverInterface
    {
        if (empty($driver)) {
            $driver = DriverFactory::makeSqlDriver();
        }
        return $driver;
    }

    public static function getUserRepository(?DriverInterface $driver = null): UserRepositorySql
    {
        $driver = self::getDriver($driver);
        $userRepository = new UserRepositorySql($driver);
        return $userRepository;
    }

    public static function getTransactionRepository(?DriverInterface $driver = null): TransactionRepositorySql
    {
        $driver = self::getDriver($driver);
        $transactionRepositorySql = new TransactionRepositorySql($driver);
        return $transactionRepositorySql;
    }
}