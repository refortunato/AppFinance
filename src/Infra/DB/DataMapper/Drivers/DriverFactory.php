<?php

namespace AppFinance\Infra\DB\DataMapper\Drivers;

class DriverFactory
{
    /**
     * Configurações para conexão estão em ../../../../Config/DatabaseConf.php que é importado em /public/index.php
     */
    public static function makeSqlDriver(): DriverInterface
    {
        $conn_driver = new MySql();
        $conn_driver->connect([
            'server' => DB_SERVER,
            'database' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD
        ]);
        return $conn_driver;
    }
}
