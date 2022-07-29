<?php

namespace App\Models\DAO;

use App\Lib\Conexao;
use App\Models\Entidades\Usuario;
use Exception;

class UsuarioDAO extends BaseDAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function buscarPorEmail($email)
    {
        $resultado = $this->select(
            "SELECT * FROM usuario WHERE email = '{$email}'"
        );

        return $resultado->fetchObject(Usuario::class);
    }

    public function salvar(Usuario $usuario)
    {
        $idUsuario = null;
        try {
            $this->insert(
                'usuario',
                ':email,:login,:senha,:status',
                [
                    ':email' => $usuario->getEmail(),
                    ':login' => $usuario->getLogin(),
                    ':senha' => $usuario->getSenha(),
                    ':status' => $usuario->getStatus()
                ]
            );

            $idUsuario = Conexao::getConnection()->lastInsertId();

            if (empty($idUsuario)) {
                throw new Exception('Impossível determinar o último id gerado');
            }
        } catch (Exception $e) {
            throw new Exception("Erro na gravação de dados. " . $e->getMessage(), 500);
        }

        return $idUsuario;
    }

    public function ativar(Usuario $usuario)
    {
        try {
            $this->update(
                'usuario',
                'status = :status',
                [
                    ':id' => $usuario->getId(),
                    ':status' => 1
                ],
                'id = :id'
            );
        } catch (Exception $e) {
            throw new Exception("Erro na gravação de dados. user " . $e->getMessage(), 500);
        }
    }
}