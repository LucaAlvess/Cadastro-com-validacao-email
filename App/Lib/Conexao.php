<?php

namespace App\Lib;

use PDOException;
use Exception;
use PDO;

class Conexao
{
    private static $connection;

    private function __construct()
    {
    }

    public static function getConnection()
    {
        $pdoConfig = DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';';

        try {
            if (!isset(self::$connection)) {
                self::$connection = new PDO($pdoConfig, DB_USER, DB_PASSWORD);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$connection;
        } catch (PDOException $e) {
            throw new Exception("Erro de conexao com o banco de dados: {$e->getMessage()}", 500);
        }
    }
}