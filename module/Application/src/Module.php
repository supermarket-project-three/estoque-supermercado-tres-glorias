<?php

declare(strict_types=1);

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;

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
        $rotaAtual = $e->getRouteMatch()->getMatchedRouteName();

        // Rotas que NÃO precisam de login
        $rotasPublicas = [
            'login',
            'autenticar',
        ];

        if (in_array($rotaAtual, $rotasPublicas)) {
            return;
        }

        // Se a rota NÃO é pública, verifica a sessão
        $session = new Container('user');
        
        if (!isset($session->id)) {
            // NÃO ESTÁ LOGADO!
            
            $url = $e->getRouter()->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            
            return $response;
        }

        // Se chegou até aqui, o utilizador está logado.
        
        // --- INÍCIO DA CORREÇÃO ---
        // A variável $e JÁ É o MvcEvent
        $viewModel = $e->getViewModel();
        // --- FIM DA CORREÇÃO ---
        
        $viewModel->setVariable('user', $session);
    }
}