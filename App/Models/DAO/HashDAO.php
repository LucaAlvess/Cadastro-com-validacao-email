<?php

namespace App\Models\DAO;

use App\Lib\Conexao;
use App\Models\Entidades\Hash;
use Exception;

class HashDAO extends BaseDAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function listarPorIdUsuario($id = null)
    {
        $resultado = $this->select(
            "SELECT * FROM hash WHERE id_usuario = $id"
        );

        return $resultado->fetchObject(Hash::class);
    }

    public function salvar(Hash $hash)
    {
        $idHash = null;

        try {
            $this->insert(
                'hash',
                ':hash,:status,:id_usuario',
                [
                    ':hash' => $hash->getHash(),
                    ':status' => $hash->getStatus(),
                    ':id_usuario' => $hash->getIdUsuario()
                ]
            );

            $idHash = Conexao::getConnection()->lastInsertId();

            if (empty($idHash)) {
                throw new Exception('Impossível determinar o último id gerado');
            }
        } catch (Exception $e) {
            throw new Exception('Erro na gravação dos dados ' . $e->getMessage(), 500);
        }

        return $idHash;
    }

    public function ativar(Hash $hash)
    {
        try {
            $this->update(
                'hash',
                'status = :status',
                [
                    ':id' => $hash->getId(),
                    ':status' => 1
                ],
                'id = :id'
            );
        } catch (Exception $e) {
            throw new Exception('Erro na gravação de dados hash ' . $e->getMessage(), 500);
        }
    }
}