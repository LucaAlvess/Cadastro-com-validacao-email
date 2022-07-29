<?php

namespace App\Models\Validacao\Hash;

use App\Models\Entidades\Hash;
use App\Models\Validacao\ResultadoValidacao;

class HashCadastroValidador
{
    public function validar(Hash $hash)
    {
        $resultadoValidacao = new ResultadoValidacao();

        if (empty($hash)) {
            $resultadoValidacao->addErro('status', 'Chave inválida');
        }

        if (empty($hash->getIdUsuario())) {
            $resultadoValidacao->addErro('idUsuario', 'Este campo é requerido id');
        }

        if (empty($hash->getHash())) {
            $resultadoValidacao->addErro('hash', 'Este campo é requerido hash');
        }

        return $resultadoValidacao;
    }
}