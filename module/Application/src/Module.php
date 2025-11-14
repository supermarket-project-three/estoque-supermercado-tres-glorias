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



    /**
     * Este método é chamado assim que o módulo é carregado.
     * Vamos usá-lo para "escutar" os eventos da aplicação.
     */
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        
        // Regista um "escutador" no evento de 'dispatch' (antes de carregar o controller)
        // Quando o evento 'dispatch' ocorrer, ele chamará o método 'protegerPaginas'
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [$this, 'protegerPaginas'], 100);
    }

    /**
     * A nossa função de verificação de login
     * (Será chamada em TODAS as requisições)
     */
    public function protegerPaginas(MvcEvent $e)
    {
        $rotaAtual = $e->getRouteMatch()->getMatchedRouteName();
        $controlador = $e->getRouteMatch()->getParam('controller');

        // Lista de rotas que NÃO precisam de login
        $rotasPublicas = [
            'login',
            'autenticar',
            // Adicione aqui outras rotas públicas (ex: 'sobre', 'contato')
        ];

        if (in_array($rotaAtual, $rotasPublicas)) {
            // Se a rota é pública (ex: /login), não faz nada
            return;
        }

        // Se a rota NÃO é pública, verifica a sessão
        $session = new Container('user');
        
        if (!isset($session->id)) {
            // NÃO ESTÁ LOGADO!
            // Redireciona para a página de login
            
            $url = $e->getRouter()->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302); // 302 é um redirecionamento temporário
            
            // Impede o resto da aplicação de continuar
            return $response;
        }

        // Se chegou até aqui, o utilizador está logado.
        
        // Opcional: Injeta os dados do utilizador em TODAS as views
        // (Útil para mostrar "Olá, Carlos" no layout)
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->setVariable('user', $session);
    }
    
}