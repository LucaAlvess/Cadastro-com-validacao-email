<?php

namespace App\Models\Validacao\Hash;

use App\Models\Entidades\Hash;
use App\Models\Validacao\ResultadoValidacao;
use DateTime;

class HashAtivacaoValidador
{
    public function validar(Hash $hash)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $resultadoValidacao = new ResultadoValidacao();

        if (empty($hash)) {
            $resultadoValidacao->addErro('status', "Chave inválida");
        }

        if ($hash->getStatus() === 1) {
            $resultadoValidacao->addErro('status', "Chave já está ativa");
        }

        $dataAtual = new DateTime('now');
        $dataCadastro = $hash->getDataCadastro();
        $dataCadastro = new DateTime("{$dataCadastro}");
        $diferenca = $dataAtual->diff($dataCadastro);

        if (($diferenca->h + ($diferenca->days * 24)) > 72) {
            $resultadoValidacao->addErro('status', "Chave expirada");
        }

        return $resultadoValidacao;
    }
}