<?php

namespace Ares;

use Ares\Database\Db;

/**
 * App
 * A static class designed to house all the loose function as the site is rebuilt
 */
class App
{
    /**
     * @var \Ares\Database\Db
     */
    protected static $connection;

    /**
     * @return \Ares\Database\Db|bool|\mysqli
     */
    public static function getDbConnection()
    {
        if (!empty(static::$connection)) {
            return static::$connection;
        }

        return static::$connection = Db::connect();
    }
}

