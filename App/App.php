<?php

namespace App;

use App\Controllers\HomeController;
use Exception;

class App
{
    private $controller;
    private $controllerFile;
    private $action;
    private $params;
    public $controllerName;

    public function __construct()
    {
        define('APP_HOST', $_SERVER['HTTP_HOST'] . '/');
        define('TITLE', 'Meu App');

        define('DB_HOST', '127.0.0.1');
        define('DB_PORT', 3306);
        define('DB_USER', 'root');
        define('DB_PASSWORD', 'gogKN<Ua363765632514');
        define('DB_NAME', 'php_email');
        define('DB_DRIVER', 'mysql');

        $this->url();
    }

    private function url()
    {
        if (isset($_GET['url'])) {
            //Captura a string da requisição
            $path = $_GET['url'];
            //Retira os espaços em branco
            $path = rtrim($path, '/');
            //Filtra a string da url
            $path = filter_var($path, FILTER_SANITIZE_URL);

            //Separa a string da url com ocorrência de '/'
            $path = explode('/', $path);

            //Verifica o primeiro valor(url) nome(construtor)
            $this->controller = $this->verificaArray($path, 0);
            //Verifica o segundo valor(url) ação(método)
            $this->action = $this->verificaArray($path, 1);

            //Caso exista um terceiro valor(parâmetros para a função)
            if ($this->verificaArray($path, 2)) {
                //Deleta as ocorrências anteriores
                unset($path[0]);
                unset($path[1]);
                //Armazena os valores dos parâmetros
                $this->params = array_values($path);
            }
        }
    }

    private function verificaArray(array $array, mixed $key)
    {
        if (isset($array[$key]) && !empty($array[$key])) {
            return $array[$key];
        }
        return null;
    }

    public function run()
    {
        if ($this->controller) {
            //Captura o primeiro parâmetro e coloca primeira letra maiuscula e concatena com 'Controller'
            $this->controllerName = ucwords($this->controller) . 'Controller';
            //Retira as ocorrências de certos caracteres
            $this->controllerName = preg_replace('/[^a-zA-Z]/i', '', $this->controllerName);
        } else {
            //Padrão é home controller
            $this->controllerName = 'HomeController';
        }

        //Adiciona a extensão para a classe(constrolador)
        $this->controllerFile = $this->controllerName . '.php';
        //Retira as ocorrências de certos caracteres
        if ($this->action) {
            $this->action = preg_replace('/[^a-zA-Z]/i', '', $this->action);
        }


        //Caso não exista controller
        if (!$this->controller) {
            //Instância a classe(controlador) home
            $this->controller = new HomeController($this);
            //Chama um método index para renderiar a página
            $this->controller->index();
        }

        //Caso não exista o arquivo(classe controladora) retorna uma exceção
        if (!file_exists(__DIR__ . '/Controllers/' . $this->controllerFile)) {
            throw new Exception('Página não encontrada', 404);
        }

        //Captura o nome da classe com seu namespace
        $nomeCLasse = "\\App\\Controllers\\" . $this->controllerName;
        //Instância a classe
        $objetoController = new $nomeCLasse($this);

        //Se não existe a classe lança uma exceção
        if (!class_exists($nomeCLasse)) {
            throw new Exception("Erro na aplicação", 500);
        }

        //Se o método existe
        if ($this->action && method_exists($objetoController, $this->action)) {
            //Chama a classe passando os parâmetros
            $objetoController->{$this->action}($this->params);
//            call_user_func([$objetoController, $this->action], $this->params);
            return;
            //se não existe ação e método index existe,
        } else if (!$this->action && method_exists($objetoController, 'index')) {
            //chama a index da home
            $objetoController->index($this->params);
            return;
        } else {
            throw new Exception("Nosso suporte já está avaliando", 500);
        }
        throw new Exception("Página não encontrada", 404);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getParams()
    {
        return $this->params;
    }
}