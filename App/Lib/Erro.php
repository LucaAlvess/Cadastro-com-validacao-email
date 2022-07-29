<?php

namespace App\Lib;

use Exception;

class Erro
{
    private $message;
    private $code;

    public function __construct(Exception $exception)
    {
        $this->code = $exception->getCode();
        $this->message = $exception->getMessage();
    }

    public function render()
    {
        $varMessage = $this->message;

        if (file_exists(__DIR__ . '/../Views/error/' . $this->code . '.php')) {
            require_once __DIR__ . '/../Views/error/' . $this->code . '.php';
        } else {
            require_once __DIR__ . '/../Views/error/500.php';
        }
        exit;
    }
}