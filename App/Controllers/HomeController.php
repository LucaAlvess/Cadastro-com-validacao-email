<?php

namespace App\Controllers;

use App\Lib\Sessao;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home/index');

        Sessao::limpaMensagem();
        Sessao::limpaFormulario();
    }
}