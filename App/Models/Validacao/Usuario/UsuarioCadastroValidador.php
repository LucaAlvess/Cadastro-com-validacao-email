<?php

namespace App\Models\Validacao\Usuario;

use App\Models\Entidades\Usuario;
use App\Models\Validacao\ResultadoValidacao;

class UsuarioCadastroValidador
{
    public function validar(Usuario $usuario)
    {
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($usuario)) {
            $resultadoValidacao->addErro('status', 'Chave inválida');
        }

        if (empty($usuario->getSenha())) {
            $resultadoValidacao->addErro('status', 'Este campo é requerido senha');
        }

        if (empty($usuario->getLogin())) {
            $resultadoValidacao->addErro('status', 'Informe pelo menos 2 caracteres');
        }

        if (empty($usuario->getEmail())) {
            $resultadoValidacao->addErro('status', 'Este campo é requerido email');
        }

        return $resultadoValidacao;
    }
}