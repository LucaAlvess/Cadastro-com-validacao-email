<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Lib\Criptografia;
use App\Lib\Email;
use App\Models\DAO\HashDAO;
use App\Models\DAO\UsuarioDAO;
use App\Models\Entidades\Hash;
use App\Models\Entidades\Usuario;
use App\Models\Validacao\Hash\HashAtivacaoValidador;
use App\Models\Validacao\Hash\HashCadastroValidador;
use App\Models\Validacao\Usuario\UsuarioCadastroValidador;
use Exception;

class UsuarioController extends Controller
{
    public function index(): void
    {
        $this->render('usuario/index');

        Sessao::limpaMensagem();
    }

    public function cadastrar(): void
    {
        Sessao::gravaFormulario($_POST);

        try {
            $usuarioDao = new UsuarioDAO();
            $usuario = $usuarioDao->buscarPorEmail($_POST['email']);

            if (!empty($usuario)) {
                if ($usuario->getStatus() == 1) {
                    Sessao::gravaMensagem("Usuário já cadastrado.");

                    $this->redirect('home/index');
                } else if ($usuario->getStatus() == 0) {
                    $this->redirect('usuario/ativacao');
                }
            }

            $usuario = new Usuario();
            $usuario->setEmail($_POST['email']);
            $usuario->setLogin($_POST['login']);
            $usuario->setStatus(0);
            $usuario->setSenha(CriptoGrafia::criptografar($_POST['senha']));

            $usuarioValidador = new UsuarioCadastroValidador();
            $resultadoValidacao = $usuarioValidador->validar($usuario);
            $erros = $resultadoValidacao->getErros();

            if (!empty($erros)) {
                $mensagem = implode('\n', $erros);

                Sessao::gravaMensagem($mensagem);

                $this->redirect('home/index');
            }

            $idUsuario = $usuarioDao->salvar($usuario);
            $usuario->setId($idUsuario);

            $hash = new Hash();
            $hash->setStatus(0);
            $hash->setIdUsuario($usuario->getId());
            $hash->setHash(CriptoGrafia::criptografar($usuario->getEmail()));

            $hashValidador = new HashCadastroValidador();
            $resultadoValidacao = $hashValidador->validar($hash);
            $erros = $resultadoValidacao->getErros();

            if (!empty($erros)) {
                $mensagem = implode('\n', $erros);

                Sessao::gravaMensagem($mensagem);

                $this->redirect('home/index');
            }

            $hashDao = new HashDAO();
            $hashDao->salvar($hash);

            Email::enviarEmailConfirmacaoCadastro($usuario, $hash);

            Sessao::gravaMensagem('Só falta um passo :) Você pode confirmar seu cadastro seu cadastro através da mensagem enviada para o seu e-mail?');

            $this->redirect('usuario/ativacao');
        } catch (Exception $e) {
            Sessao::gravaMensagem($e->getMessage());
        }

        $this->render('home/index');

        Sessao::limpaMensagem();
        Sessao::limpaFormulario();
    }

    public function ativacao($params): void
    {
        try {
            if (empty($params)) {
                throw new Exception('Use o link na mensagem enviada para o seu e-mail para ativar o seu cadastro.');
            }

            $email = Criptografia::descriptografar($params[0]);

            $usuarioDao = new UsuarioDAO();
            $usuario = $usuarioDao->buscarPorEmail($email);

            if (empty($usuario)) {
                Sessao::limpaFormulario();

                throw new Exception('Usuário não encontrado.');
            }

            $hashDao = new HashDAO();
            $hash = $hashDao->listarPorIdUsuario($usuario->getId());

            if (!empty($hash) && $hash->getStatus() == 1) {
                Sessao::limpaFormulario();

                throw new Exception('A chave já foi cofirmada.');
            }

            $hashValidador = new HashAtivacaoValidador();
            $resultadoVlidacao = $hashValidador->validar($hash);

            $erros = $resultadoVlidacao->getErros();

            if (!empty($erros)) {
                $mensagem = implode('\n', $erros);

                throw new Exception($mensagem);
            }

            if ($hash->getHash() !== $params[0]) {
                Sessao::limpaFormulario();

                throw new Exception('A chave não está associada ao usuário de origem.');
            }

            $hashDao->ativar($hash);
            $usuarioDao->ativar($usuario);

            $this->redirect('usuario/index');
        } catch (Exception $e) {
            Sessao::gravaMensagem($e->getMessage());
        }

        $this->render('usuario/ativacao');
        Sessao::limpaMensagem();
    }

    public function reenviar(): void
    {
        $usuarioDao = new UsuarioDAO();
        $usuario = $usuarioDao->buscarPorEmail($_POST['email']);

        if (empty($usuario)) {
            throw new Exception('Usuário não encontrado.');
        }

        $hashDao = new HashDAO();
        $hash = $hashDao->listarPorIdUsuario($usuario->getId());

        if (!empty($hash) && $hash->getStatus() == 1) {
            Sessao::gravaMensagem('A chave já foi confirmada.');

            $this->redirect('home/index');
        }

        $hashValidador = new HashAtivacaoValidador();
        $resultadoValidacao = $hashValidador->validar($hash);

        $erros = $resultadoValidacao->getErros();

        if (!empty($erros)) {
            $mensagem = implode('\n', $erros);

            throw new Exception($mensagem);
        }

        Email::enviarEmailConfirmacaoCadastro($usuario, $hash);

        Sessao::gravaMensagem('Reenviamos o email');

        $this->render('usuario/ativacao');

        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
    }
}