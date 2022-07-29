<?php

namespace App\Lib;

class Email
{
    public static function enviarEmailConfirmacaoCadastro($usuario, $hash)
    {
        self::enviar(
            $usuario->getEmail(),
            $usuario->getLogin(),
            'Confirmação de email',
            "<p>Clique <a href='http://" . APP_HOST . "usuario/ativacao/{$hash->getHash()}'>Aqui</a>.</p>",
            "usuario/cadastrar/{$hash->getHash()} para ativar o seu cadastro");
    }

    private static function enviar($para, $nome, $titulo, $html, $texto)
    {
        $mail = new \PHPMailer();
        $mail->isSMTP(true);
        $mail->Host = 'SMTP.office365.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "lucas.dev.teste@hotmail.com";
        $mail->Password = 'Lucas3765632514';
        $mail->setFrom("lucas.dev.teste@hotmail.com", 'Lucas');
        $mail->addReplyTo("replyto@meusite.com", 'Lucas');
        $mail->addAddress($para, $nome);
        $mail->Subject = $titulo;
        $mail->CharSet = 'UTF-8';
        $mail->msgHTML($html);
        $mail->AltBody = $texto;
        //Adciona uma imagem
        $mail->addAttachment('');

        if(!$mail->send())
        {
            throw new \Exception("Erro no envio do e-mail: {$mail->ErrorInfo}");
        }
    }
}