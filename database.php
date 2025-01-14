<?php
require_once __DIR__.'/../php_config/config.php';

config::setFromFile();

final class database
{
    public static $mysqlObject;

    final private static function error(): never
    {
        $redirect = config::$config['database']['redirect_on_error'];
        if (is_string(value: $redirect))
            header(header: "Location: $redirect");
        else
            echo 'Unable to connect to the database';
        die();
    }

    final public static function connect(string $databaseName, $config = null): bool
    {
        global $mysqlObject;

        if ($config === null && isset(config::$config['database']))
            $config = config::$config['database'];

        $mysqlObject = new mysqli
        (
            hostname: $config['address'],
            username: $config['username'],
            password: $config['password'],
            database: $databaseName
        );

        if (mysqli_connect_error())
            self::error();
        else
            return true;
    }

    final public static function disconnect(): void
    {
        global $mysqlObject;
        $mysqlObject -> close();
    }

    final public static function sql(string $yourSqlQuery, bool $getResults = false): mixed
    {
        global $mysqlObject;
        if ($getResults === true)
            return $mysqlObject -> query($yourSqlQuery) -> fetch_assoc();
        else
            return $mysqlObject -> query($yourSqlQuery);
    }
}