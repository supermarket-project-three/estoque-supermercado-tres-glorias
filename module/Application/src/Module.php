<?php

declare(strict_types=1);

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Http\Response;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [$this, 'protegerPaginas'], 100);
    }

    public function protegerPaginas(MvcEvent $e)
    {
        //Verifica se a rota existe (evita erro em páginas 404)
        $match = $e->getRouteMatch();
        if (!$match) {
            return;
        }

        $rotaAtual = $match->getMatchedRouteName();

        //Rotas Públicas (Ninguém precisa de login)
        $rotasPublicas = ['login', 'autenticar', 'logout'];
        if (in_array($rotaAtual, $rotasPublicas)) {
            return;
        }

        //Verifica Login (Se não tiver sessão, manda para login)
        $session = new Container('user');
        
        if (!isset($session->id)) {
            $url = $e->getRouter()->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            
            // Verificação de tipo para corrigir o erro do editor
            if ($response instanceof Response) {
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
            }
            
            return $response;
        }

        //Controle de Acesso por tipo de usuário
        
        // Lista de rotas de Administrador
        $rotasAdmin = [
            'dashboard', 
            'dashboard-usuarios', 'dashboard-usuarios-salvar', 'dashboard-usuarios-editar', 'dashboard-usuarios-atualizar', 'dashboard-usuarios-apagar',
            'dashboard-setores', 'dashboard-setores-salvar', 'dashboard-setores-apagar',
            'dashboard-produtos', 'dashboard-produtos-salvar', 'dashboard-produtos-editar', 'dashboard-produtos-atualizar', 'historico',
        ];

        // Se o usuário é um responsavel e tenta acessar rota de admin, bloqueia
        if ($session->tipo === 'responsavel' && in_array($rotaAtual, $rotasAdmin)) {
            
            // Redireciona o invasor de volta para a área dele
            $url = $e->getRouter()->assemble([], ['name' => 'estoque']);
            $response = $e->getResponse();
            
            // Verificação de tipo aqui também
            if ($response instanceof Response) {
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
            }
            
            return $response;
        }

        //Injeta dados na view
        $viewModel = $e->getViewModel();
        $viewModel->setVariable('user', $session);
    }
}