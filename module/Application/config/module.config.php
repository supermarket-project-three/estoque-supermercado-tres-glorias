<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            // Rota Inicial
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
            
            //Rotas de Autenticação
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login', // URL de login
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
                    'route'    => '/logout', //URL para fazer logout
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            
            //Rotas de Destino (só serão permitidas após o login)
            'dashboard' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'estoque' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/estoque',
                    'defaults' => [
                        'controller' => Controller\EstoqueController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            //Rotas de Gestão (Dashboard)
            'dashboard-usuarios' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-usuarios', // O URL da página de gestão de usuarios
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard-usuarios-salvar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-usuarios/salvar', // O URL que o form envia para salvar o usuario
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'salvar',
                    ],
                ],
            ],

            'dashboard-usuarios-apagar' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-usuarios/apagar/:id', // O URL com o parâmetro :id para apagar o usuario
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
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-usuarios/editar/:id', //Rota de edição de usuário
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'editar',
                    ],
                ],
            ],
            
            'dashboard-usuarios-atualizar' => [
                'type'    => Segment::class, 
                'options' => [
                    'route'    => '/dashboard-usuarios/atualizar/:id', //Rota que envia os dados editados
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'atualizar',
                    ],
                ],
            ],

            'dashboard-setores' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-setores',
                    'defaults' => [
                        'controller' => Controller\SetorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard-setores-salvar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-setores/salvar',
                    'defaults' => [
                        'controller' => Controller\SetorController::class,
                        'action'     => 'salvar',
                    ],
                ],
            ],
            'dashboard-setores-apagar' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-setores/apagar/:id',
                    'constraints' => ['id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\SetorController::class,
                        'action'     => 'apagar',
                    ],
                ],
            ],

            'dashboard-produtos' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-produtos',
                    'defaults' => [
                        'controller' => Controller\ProdutoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard-produtos-salvar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard-produtos/salvar',
                    'defaults' => [
                        'controller' => Controller\ProdutoController::class,
                        'action'     => 'salvar',
                    ],
                ],
            ],
            // (Opcional: Rota para editar/apagar depois)
            'dashboard-produtos-apagar' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-produtos/apagar/:id',
                    'constraints' => ['id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\ProdutoController::class,
                        'action'     => 'apagar',
                    ],
                ],
            ],

            'dashboard-produtos-editar' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-produtos/editar/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProdutoController::class,
                        'action'     => 'editar',
                    ],
                ],
            ],
            'dashboard-produtos-atualizar' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard-produtos/atualizar/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProdutoController::class,
                        'action'     => 'atualizar',
                    ],
                ],
            ],

            'movimentacao' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/movimentacao',
                    'defaults' => [
                        'controller' => Controller\MovimentacaoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'movimentacao-salvar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/movimentacao/salvar',
                    'defaults' => [
                        'controller' => Controller\MovimentacaoController::class,
                        'action'     => 'salvar',
                    ],
                ],
            ],

            'historico' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/historico',
                    'defaults' => [
                        'controller' => Controller\HistoricoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            
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

    // Registo do Serviço do Doctrine
    'service_manager' => [
        'factories' => [
            Service\DoctrineService::class => InvokableFactory::class,
        ],
    ],

    // Registo das Factories dos Controladores
    'controllers' => [
        'factories' => [
            // Factory para o IndexController (para injetar o Doctrine)
            Controller\IndexController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\IndexController($doctrineService);
            },
            
            // Controlador de Autenticação (Login/Logout)
            Controller\AuthController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\AuthController($doctrineService);
            },

            // Controlador de Gestão de Usuários(Dashboard)
            Controller\UsuarioController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\UsuarioController($doctrineService);
            },

            //Controlador dos Setores (Dashboard)
            Controller\SetorController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\SetorController($doctrineService);
            },

            //Controlador do Estoque
            Controller\EstoqueController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\EstoqueController($doctrineService);
            },

            //Controlador de Produtos
            Controller\ProdutoController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\ProdutoController($doctrineService);
            },

            Controller\MovimentacaoController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\MovimentacaoController($doctrineService);
            },

            Controller\HistoricoController::class => function($container) {
                $doctrineService = $container->get(Service\DoctrineService::class);
                return new Controller\HistoricoController($doctrineService);
            },
        ],
    ],
    
    // Views
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
            // View da Gestão de usuários
            'application/usuario/index' => __DIR__ . '/../view/application/usuario/index.phtml', 
            //View da Edição de usuários
            'application/usuario/editar' => __DIR__ . '/../view/application/usuario/editar.phtml',
            //View da Gestão de setores
            'application/setor/index' => __DIR__ . '/../view/application/setor/index.phtml',
            //View do Estoque
            'application/estoque/index' => __DIR__ . '/../view/application/estoque/index.phtml',
            //View de Produtos
            'application/produto/index' => __DIR__ . '/../view/application/produto/index.phtml',
            'application/produto/editar' => __DIR__ . '/../view/application/produto/editar.phtml',
            // View de Movimentação
            'application/movimentacao/index' => __DIR__ . '/../view/application/movimentacao/index.phtml',
            'application/historico/index' => __DIR__ . '/../view/application/historico/index.phtml',

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];