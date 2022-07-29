<?php

namespace App\Models\DAO;

use App\Lib\Conexao;

abstract class BaseDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConnection();
    }

    public function select($sql)
    {
        if (!empty($sql)) {
            return $this->conn->query($sql);
        }
        return false;
    }

    public function insert($table, $cols, $value)
    {
        if (!empty($table) && !empty($cols) && !empty($value)) {
            $parametros = $cols;
            $cols = str_replace(':', '', $cols);

            $stmt = $this->conn->prepare("INSERT INTO $table ($cols) VALUES ($parametros)");
            $stmt->execute($value);

            return $stmt->rowCount();
        } else {
            return false;
        }
    }

    public function update($table, $cols, $values, $where = null)
    {
        if (!empty($table) && !empty($cols) && !empty($values)) {
            if ($where) {
                $where = " WHERE " . $where;
            }

            $stmt = $this->conn->prepare("UPDATE $table SET $cols $where");
            $stmt->execute($values);

            return $stmt->rowCount();
        } else {
            return false;
        }
    }

    public function delete($table, $where = null)
    {
        if (!empty($table)) {
            if ($where) {
                $where = " WHERE $where";
            }

            $stmt = $this->conn->prepare("DELETE FROM $table $where");
            $stmt->execute();

            return $stmt->rowCount();
        } else {
            return false;
        }
    }
}