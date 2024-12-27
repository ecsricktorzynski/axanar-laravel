<?php

namespace Ares\Database;

use mysqli;

class Db
{
    /**
     * @var \mysqli
     */
    protected static $connection;

    /**
     * @param array $config
     *
     * @return bool|\mysqli
     */
    public static function connect(array $config = [])
    {
        if (empty(static::$connection)) {
            $config = empty($config) ? parse_ini_file(__DIR__ . '/../../config/config.ini') : $config;
            static::$connection = new mysqli($config['host'], $config['username'], $config['password'], $config['name'], $config['port'] ?? 3306);
            static::$connection->set_charset('utf8');

            if (static::$connection === false) {
                throw new \RuntimeException('Database unavailable');
            }
        }

        if (static::$connection->connect_errno) {
            echo 'Failed to connect to MySQL: (' . static::$connection->connect_errno . ') ' . static::$connection->connect_error;
        }

        return static::$connection;
    }

    protected static function referenceValues(array $values = [])
    {
        $references = [];
        foreach ($values as $key => $value) {
            $references[$key] = &$values[$key];
        }
        return $references;
    }

    /**
     * @param string $sql
     * @param array  $binds
     *
     * @return bool|\mysqli_result|array|int
     */
    public static function query($sql, array $binds = [])
    {
        $db = static::connect();

        if (empty($binds)) {
            $result = $db->query($sql);
            return $result;
        }

        try {
            if (false === ($statement = $db->prepare($sql))) {
                return false;
            }

            $types = $bindRefs = null;
            foreach ($binds as $key => $value) {
                $types .= is_numeric($value) ? 'i' : 's';
                $bindRefs[] = '$binds[' . $key . ']';
            }
            $bindRefs = implode(', ', $bindRefs);

            $code = <<<PHP
\$result = \$statement->bind_param(\$types, ${bindRefs} );
PHP;
            eval($code);

            if (false === ($result = $statement->execute())) {
                return false;
            }

            //  Non-SELECT statements return # of rows
            if (stripos(trim($sql), 'SELECT') === false) {
                return $statement->affected_rows;
            }

            $rows = static::fetchArray($statement);
            $statement->close();

            return $rows;
        }
        catch (\Exception $ex) {
            addToAudit('Db::query', null, 'failed to query database', ['message' => $ex->getMessage(), 'code' => $ex->getCode()]);
            return false;
        }
    }

    /**
     * @param \mysqli_stmt $statement
     *
     * @return array|bool
     */
    protected static function fetchArray(\mysqli_stmt $statement): array
    {
        try {
            $meta = $statement->result_metadata();
            $params = $row = $result = [];

            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }

            call_user_func_array([$statement, 'bind_result'], $params);

            while ($statement->fetch()) {
                $fetched = [];
                foreach ($row as $key => $value) {
                    $fetched[$key] = $value;
                }
                $result[] = $fetched;
                unset($fetched);
            }

            return $result;
        }
        catch (\Exception $ex) {
            addToAudit('Db::fetchArray', null, 'failed to fetch statement results', ['message' => $ex->getMessage(), 'code' => $ex->getCode()]);
        }

        return false;
    }

    /**
     * @param string $query
     * @param array  $bindings
     *
     * @return array|bool|mixed
     * @retur array|bool
     */
    public static function select($query, array $bindings = [])
    {
        $response = \Illuminate\Support\Facades\DB::select($query, $bindings);

        $result = [];
        foreach ($response as $object) {
            $result[] = (array)$object;
        }

        return $result;
    }

    /**
     * @return string
     */
    public static function error()
    {
        return static::connect()->error;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function quote($value)
    {
        return "'" . static::connect()->real_escape_string($value) . "'";
    }
}
