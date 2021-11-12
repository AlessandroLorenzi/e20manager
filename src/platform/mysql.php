<?php

namespace Platform;

class MySQL
{
    static function connect(): \PDO
    {
        $host = getenv("MYSQL_HOST");
        $dbname = getenv("MYSQL_DATABASE");
        $username = getenv("MYSQL_USER");
        $password = getenv("MYSQL_PASSWORD");

        $conn = new \PDO(
            "mysql:host=${host};dbname=${dbname};charset=utf8mb4",
            $username,
            $password
        );
        return $conn;
    }
}
