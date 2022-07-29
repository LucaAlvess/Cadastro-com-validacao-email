<?php

namespace App\Controllers;

use App\App;
use App\Lib\Sessao;

abstract class Controller
{
    protected $app;
    private $viewVar;

    public function __construct(App $app)
    {
        $this->setViewParam('nameController', $app->getControllerName());
    }

    public function render(string $view)
    {
        $viewVar = $this->getViewVar();
        $sessao = Sessao::class;

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/menu.php';
        require_once __DIR__ . '/../Views/' . $view . '.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    #[NoReturn] public function redirect(string $view)
    {
        header('Location: http://' . APP_HOST . $view);
        exit;
    }

    private function getViewVar()
    {
        return $this->viewVar;
    }

    private function setViewParam(string $varName, string $varValue)
    {
        if ($varName != '' && $varValue != '') {
            $this->viewVar[$varName] = $varValue;
        }
    }
}