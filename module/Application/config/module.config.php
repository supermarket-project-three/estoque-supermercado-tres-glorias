<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
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


            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login', // URL para MOSTRAR a página de login
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index', // Chama o método indexAction
                    ],
                ],
            ],
            'autenticar' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/autenticar', // URL para PROCESSAR o login (POST)
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'autenticar', // Chama o método autenticarAction
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

    'service_manager' => [
        'factories' => [
            Service\DoctrineService::class => InvokableFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            
            //criar o AuthController
            Controller\AuthController::class => function($container) { // <<< ADICIONADO
                // Pega o DoctrineService
                $doctrineService = $container->get(Service\DoctrineService::class);
                
                // Cria o Controller e "injeta" o serviço nele
                return new Controller\AuthController($doctrineService);
            },
        ],
    ],
    
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            
            // Adiciona o caminho para a view de login
            'application/auth/index' => __DIR__ . '/../view/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];