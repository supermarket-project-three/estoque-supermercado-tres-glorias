<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // Tenta aceder à sessão user
        $session = new Container('user');
        $nomeUsuario = null;

        if (isset($session->id)) {
            // Se a sessão existe, pega o nome
            $nomeUsuario = $session->nome;
        }

        // Envia o nome para a view
        return new ViewModel([
            'nomeUsuario' => $nomeUsuario
        ]);
    }
}