<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            // Rota Padrão (Home)
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            // --- Rotas de Autenticação ---
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login', // URL para MOSTRAR a página de login
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'autenticar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/autenticar', // URL para PROCESSAR o login (POST)
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'autenticar',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            
            // --- Rotas de Destino (Pós-Login) ---
            'dashboard' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\IndexController::class, // (Por enquanto, aponta para Home)
                        'action'     => 'index',
                    ],
                ],
            ],
            'estoque' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/estoque',
                    'defaults' => [
                        'controller' => Controller\IndexController::class, // (Por enquanto, aponta para Home)
                        'action'     => 'index',
                    ],
                ],
            ],

            // --- Rotas de Gestão de Utilizadores (Dashboard) ---
            'dashboard-usuarios' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-usuarios', // O URL da página de gestão
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard-usuarios-salvar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-usuarios/salvar', // O URL que o form envia
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'salvar',
                    ],
                ],
            ],

            'dashboard-usuarios-apagar' => [
                'type'    => Segment::class, // Usa Segment para aceitar parâmetros
                'options' => [
                    'route'    => '/dashboard-usuarios/apagar/:id', // O URL com o parâmetro :id
                    'constraints' => [
                        'id' => '[0-9]+', 
                    ],
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'apagar',
                    ],
                ],
            ],

            'dashboard-usuarios-editar' => [
                'type'    => Segment::class, // Usa Segment para aceitar o :id
                'options' => [
                    'route'    => '/dashboard-usuarios/editar/:id',
                    'constraints' => [
                        'id' => '[0-9]+', // Garante que o ID é um número
                    ],
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'editar', // Chama o editarAction
                    ],
                ],
            ],
            
            'dashboard-usuarios-atualizar' => [
                'type'    => Segment::class, // Usa Segment para aceitar o :id
                'options' => [
                    'route'    => '/dashboard-usuarios/atualizar/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'atualizar', // Chama o atualizarAction
                    ],
                ],
            ],

            // Rota Padrão 'application'
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    // Registo do Serviço do Doctrine (para ser injetado)
    'service_manager' => [
        'factories' => [
            Service\DoctrineService::class => InvokableFactory::class,
        ],
    ],

    // Registo das Factories dos Controladores
    'controllers' => [
        'factories' => [
            // Controlador Padrão (Home)
            Controller\IndexController::class => InvokableFactory::class,
            
            // Controlador de Autenticação (Login/Logout)
            Controller\AuthController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\AuthController($doctrineService);
            },

            // Controlador de Gestão de Utilizadores (Dashboard)
            Controller\UsuarioController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\UsuarioController($doctrineService);
            },
        ],
    ],
    
    // Gestor de Views
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'             => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index'   => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'                 => __DIR__ . '/../view/error/404.phtml',
            'error/index'               => __DIR__ . '/../view/error/index.phtml',
            
            // View do Login
            'application/auth/index'    => __DIR__ . '/../view/application/auth/index.phtml',
            // View da Gestão de Utilizadores
            'application/usuario/index' => __DIR__ . '/../view/application/usuario/index.phtml', 
            //View da Edição de Utilizadores
            'application/usuario/editar' => __DIR__ . '/../view/application/usuario/editar.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];